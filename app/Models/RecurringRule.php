<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecurringRule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'amount',
        'frequency',
        'day_of_month',
        'weekday',
        'start_date',
        'end_date',
        'default_account_id',
        'default_category_id',
        'note',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the recurring rule.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the default account for this recurring rule.
     */
    public function defaultAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'default_account_id');
    }

    /**
     * Get the default category for this recurring rule.
     */
    public function defaultCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'default_category_id');
    }

    /**
     * Scope a query to only include active recurring rules.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include recurring rules for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get the next due date for this recurring rule.
     */
    public function getNextDueDate(): ?string
    {
        if (!$this->is_active) {
            return null;
        }

        $today = now();
        $startDate = $this->start_date;
        
        if ($this->end_date && $today->gt($this->end_date)) {
            return null;
        }

        switch ($this->frequency) {
            case 'daily':
                return $today->format('Y-m-d');
            case 'weekly':
                if ($this->weekday !== null) {
                    $nextDate = $today->copy()->next($this->weekday);
                    return $nextDate->format('Y-m-d');
                }
                break;
            case 'monthly':
                if ($this->day_of_month !== null) {
                    $nextDate = $today->copy()->day($this->day_of_month);
                    if ($nextDate->lt($today)) {
                        $nextDate->addMonth();
                    }
                    return $nextDate->format('Y-m-d');
                }
                break;
            case 'yearly':
                $nextDate = $today->copy()->setDate($today->year, $startDate->month, $startDate->day);
                if ($nextDate->lt($today)) {
                    $nextDate->addYear();
                }
                return $nextDate->format('Y-m-d');
        }

        return null;
    }
}
