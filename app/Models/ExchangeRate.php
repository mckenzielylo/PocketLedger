<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExchangeRate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'from_currency',
        'to_currency',
        'rate',
        'last_updated',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rate' => 'decimal:6',
        'last_updated' => 'datetime',
    ];

    /**
     * Get the exchange rate between two currencies.
     */
    public static function getRate(string $fromCurrency, string $toCurrency): ?float
    {
        if ($fromCurrency === $toCurrency) {
            return 1.0;
        }

        $rate = static::where('from_currency', $fromCurrency)
            ->where('to_currency', $toCurrency)
            ->first();

        return $rate ? (float) $rate->rate : null;
    }

    /**
     * Convert amount from one currency to another.
     */
    public static function convert(float $amount, string $fromCurrency, string $toCurrency): ?float
    {
        $rate = static::getRate($fromCurrency, $toCurrency);
        
        if ($rate === null) {
            return null;
        }

        return $amount * $rate;
    }

    /**
     * Update or create exchange rate.
     */
    public static function updateRate(string $fromCurrency, string $toCurrency, float $rate): static
    {
        return static::updateOrCreate(
            [
                'from_currency' => $fromCurrency,
                'to_currency' => $toCurrency,
            ],
            [
                'rate' => $rate,
                'last_updated' => now(),
            ]
        );
    }

    /**
     * Get all supported currency pairs.
     */
    public static function getSupportedPairs(): array
    {
        return static::select('from_currency', 'to_currency')
            ->distinct()
            ->get()
            ->map(function ($rate) {
                return $rate->from_currency . '_' . $rate->to_currency;
            })
            ->toArray();
    }
}
