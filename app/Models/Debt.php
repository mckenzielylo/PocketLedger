<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Debt extends Model
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
        'lender',
        'principal',
        'interest_rate',
        'min_payment',
        'due_day',
        'current_balance',
        'account_id',
        'opened_on',
        'closed_on',
        'note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'principal' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'min_payment' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'opened_on' => 'date',
        'closed_on' => 'date',
    ];

    /**
     * Get the user that owns the debt.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the account for this debt.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the debt payments for this debt.
     */
    public function debtPayments(): HasMany
    {
        return $this->hasMany(DebtPayment::class);
    }

    /**
     * Scope a query to only include active debts.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('closed_on');
    }

    /**
     * Scope a query to only include debts for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get the next due date for this debt.
     */
    public function getNextDueDate(): string
    {
        $today = now();
        $nextDue = $today->copy()->day($this->due_day);
        
        if ($nextDue->lt($today)) {
            $nextDue->addMonth();
        }
        
        return $nextDue->format('Y-m-d');
    }

    /**
     * Calculate the total interest paid.
     */
    public function getTotalInterestPaidAttribute(): float
    {
        return $this->debtPayments()->sum('interest_paid');
    }

    /**
     * Calculate the total principal paid.
     */
    public function getTotalPrincipalPaidAttribute(): float
    {
        return $this->debtPayments()->sum('principal_paid');
    }

    /**
     * Calculate the remaining principal.
     */
    public function getRemainingPrincipalAttribute(): float
    {
        return $this->principal - $this->total_principal_paid;
    }
}
