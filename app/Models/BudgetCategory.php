<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'budget_id',
        'category_id',
        'limit_amount',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'limit_amount' => 'decimal:2',
    ];

    /**
     * Get the budget for this budget category.
     */
    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    /**
     * Get the category for this budget category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Calculate the total spent amount for this category in the budget month.
     */
    public function getTotalSpentAttribute(): float
    {
        $startDate = $this->budget->month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));
        
        return $this->budget->user->transactions()
            ->where('type', 'expense')
            ->where('category_id', $this->category_id)
            ->whereBetween('occurred_on', [$startDate, $endDate])
            ->sum('amount');
    }

    /**
     * Calculate the remaining budget amount for this category.
     */
    public function getRemainingAttribute(): float
    {
        return $this->limit_amount - $this->total_spent;
    }

    /**
     * Calculate the budget usage percentage for this category.
     */
    public function getUsagePercentageAttribute(): float
    {
        if (!$this->limit_amount) {
            return 0;
        }
        
        return ($this->total_spent / $this->limit_amount) * 100;
    }

    /**
     * Check if budget category is over 80% used.
     */
    public function isOverWarningThreshold(): bool
    {
        return $this->usage_percentage > 80;
    }

    /**
     * Check if budget category is exceeded.
     */
    public function isExceeded(): bool
    {
        return $this->usage_percentage > 100;
    }
}
