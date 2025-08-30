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
        'amount',
        'type',
        'interest_rate',
        'due_date',
        'description',
        'is_paid',
        'account_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'due_date' => 'date',
        'is_paid' => 'boolean',
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
     * Get the payments for this debt.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(DebtPayment::class);
    }

    /**
     * Scope a query to only include active debts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_paid', false);
    }

    /**
     * Scope a query to only include debts for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Check if the debt is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date && $this->due_date->isPast() && !$this->is_paid;
    }

    /**
     * Check if the debt is due soon (within 7 days).
     */
    public function getIsDueSoonAttribute(): bool
    {
        return $this->due_date && 
               $this->due_date->isFuture() && 
               $this->due_date->diffInDays(now()) <= 7 && 
               !$this->is_paid;
    }

    /**
     * Calculate the total amount paid.
     */
    public function getTotalPaidAttribute(): float
    {
        return $this->payments()->sum('amount');
    }

    /**
     * Calculate the remaining amount.
     */
    public function getRemainingAmountAttribute(): float
    {
        return $this->amount - $this->total_paid;
    }

    /**
     * Calculate the payment progress percentage.
     */
    public function getPaymentProgressAttribute(): float
    {
        if ($this->amount <= 0) {
            return 0;
        }
        return min(($this->total_paid / $this->amount) * 100, 100);
    }

    /**
     * Check if the debt is fully paid.
     */
    public function getIsFullyPaidAttribute(): bool
    {
        return $this->total_paid >= $this->amount;
    }
}
