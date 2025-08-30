<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'month',
        'total_limit',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_limit' => 'decimal:2',
    ];

    /**
     * Get the user that owns the budget.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the budget categories for this budget.
     */
    public function budgetCategories(): HasMany
    {
        return $this->hasMany(BudgetCategory::class);
    }

    /**
     * Scope a query to only include budgets for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include budgets for a specific month.
     */
    public function scopeForMonth($query, $month)
    {
        return $query->where('month', $month);
    }

    /**
     * Calculate the total spent amount for this budget month.
     */
    public function getTotalSpentAttribute(): float
    {
        $startDate = $this->month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));
        
        return $this->user->transactions()
            ->where('type', 'expense')
            ->whereBetween('occurred_on', [$startDate, $endDate])
            ->sum('amount');
    }

    /**
     * Calculate the remaining budget amount.
     */
    public function getRemainingAttribute(): float
    {
        if (!$this->total_limit) {
            return 0;
        }
        
        return $this->total_limit - $this->total_spent;
    }

    /**
     * Calculate the budget usage percentage.
     */
    public function getUsagePercentageAttribute(): float
    {
        if (!$this->total_limit) {
            return 0;
        }
        
        return ($this->total_spent / $this->total_limit) * 100;
    }

    /**
     * Check if budget is over 80% used.
     */
    public function isOverWarningThreshold(): bool
    {
        return $this->usage_percentage > 80;
    }

    /**
     * Check if budget is exceeded.
     */
    public function isExceeded(): bool
    {
        return $this->usage_percentage > 100;
    }
}
