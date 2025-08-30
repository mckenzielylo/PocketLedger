<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DebtPayment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'debt_id',
        'transaction_id',
        'principal_paid',
        'interest_paid',
        'paid_on',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'principal_paid' => 'decimal:2',
        'interest_paid' => 'decimal:2',
        'paid_on' => 'date',
    ];

    /**
     * Get the user that owns the debt payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the debt for this payment.
     */
    public function debt(): BelongsTo
    {
        return $this->belongsTo(Debt::class);
    }

    /**
     * Get the transaction for this payment.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Get the total amount paid.
     */
    public function getTotalPaidAttribute(): float
    {
        return $this->principal_paid + $this->interest_paid;
    }
}
