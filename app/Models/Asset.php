<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
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
        'category',
        'purchase_value',
        'current_value',
        'purchase_date',
        'depreciation_method',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'purchase_value' => 'decimal:2',
        'current_value' => 'decimal:2',
        'purchase_date' => 'date',
    ];

    /**
     * Get the user that owns the asset.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attachments for this asset.
     */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * Scope a query to only include assets for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include assets of a specific category.
     */
    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Calculate the depreciation amount.
     */
    public function calculateDepreciation(): float
    {
        if ($this->depreciation_method !== 'straight_line') {
            return 0;
        }

        $monthsSincePurchase = now()->diffInMonths($this->purchase_date);
        $totalDepreciation = $this->purchase_value - $this->current_value;
        
        return $totalDepreciation;
    }

    /**
     * Get the formatted current value.
     */
    public function getFormattedCurrentValueAttribute(): string
    {
        return 'IDR ' . number_format($this->current_value, 0, ',', '.');
    }

    /**
     * Get the formatted purchase value.
     */
    public function getFormattedPurchaseValueAttribute(): string
    {
        return 'IDR ' . number_format($this->purchase_value, 0, ',', '.');
    }
}
