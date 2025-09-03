<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'currency',
        'starting_balance',
        'current_balance',
        'is_archived',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'starting_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_archived' => 'boolean',
    ];

    /**
     * Get the user that owns the account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transactions for this account.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the transfer transactions where this account is the source.
     */
    public function transferTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'transfer_account_id');
    }

    /**
     * Get the recurring rules that use this account as default.
     */
    public function recurringRules(): HasMany
    {
        return $this->hasMany(RecurringRule::class, 'default_account_id');
    }

    /**
     * Get the debts that use this account for payments.
     */
    public function debts(): HasMany
    {
        return $this->hasMany(Debt::class);
    }

    /**
     * Scope a query to only include active accounts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    /**
     * Scope a query to only include accounts for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get the currency symbol for this account.
     */
    public function getCurrencySymbolAttribute(): string
    {
        return match ($this->currency) {
            'IDR' => 'Rp',
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            'SGD' => 'S$',
            'MYR' => 'RM',
            'THB' => '฿',
            'CAD' => 'C$',
            'AUD' => 'A$',
            'CHF' => 'CHF',
            'CNY' => '¥',
            'HKD' => 'HK$',
            'KRW' => '₩',
            'NZD' => 'NZ$',
            'INR' => '₹',
            'PHP' => '₱',
            'VND' => '₫',
            default => $this->currency,
        };
    }

    /**
     * Update the current balance based on transactions.
     */
    public function updateBalance(): void
    {
        $income = $this->transactions()
            ->where('type', 'income')
            ->sum('amount');

        $expenses = $this->transactions()
            ->where('type', 'expense')
            ->sum('amount');

        $transfersOut = $this->transactions()
            ->where('type', 'transfer')
            ->sum('amount');

        $transfersIn = $this->transferTransactions()
            ->sum('amount');

        $this->current_balance = $this->starting_balance + $income - $expenses - $transfersOut + $transfersIn;
        $this->save();
    }
}
