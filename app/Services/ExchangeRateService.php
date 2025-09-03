<?php

namespace App\Services;

use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExchangeRateService
{
    /**
     * Get exchange rate between two currencies.
     */
    public function getRate(string $fromCurrency, string $toCurrency): ?float
    {
        return ExchangeRate::getRate($fromCurrency, $toCurrency);
    }

    /**
     * Convert amount from one currency to another.
     */
    public function convert(float $amount, string $fromCurrency, string $toCurrency): ?float
    {
        return ExchangeRate::convert($amount, $fromCurrency, $toCurrency);
    }

    /**
     * Update exchange rates from external API.
     * For demo purposes, we'll use a free API or mock data.
     */
    public function updateRates(): bool
    {
        try {
            // For demo purposes, we'll use mock exchange rates
            // In production, you would use a real API like exchangerate-api.com or fixer.io
            $mockRates = $this->getMockExchangeRates();
            
            foreach ($mockRates as $pair => $rate) {
                [$from, $to] = explode('_', $pair);
                ExchangeRate::updateRate($from, $to, $rate);
            }

            Log::info('Exchange rates updated successfully');
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to update exchange rates: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get mock exchange rates for demo purposes.
     * In production, replace this with real API calls.
     */
    private function getMockExchangeRates(): array
    {
        return [
            // USD to other currencies
            'USD_IDR' => 15000.00,
            'USD_EUR' => 0.85,
            'USD_GBP' => 0.73,
            'USD_JPY' => 110.00,
            'USD_SGD' => 1.35,
            'USD_MYR' => 4.20,
            'USD_THB' => 33.00,
            'USD_CAD' => 1.25,
            'USD_AUD' => 1.40,
            'USD_CHF' => 0.92,
            'USD_CNY' => 6.45,
            'USD_HKD' => 7.80,
            'USD_KRW' => 1180.00,
            'USD_NZD' => 1.45,
            'USD_INR' => 75.00,
            'USD_PHP' => 50.00,
            'USD_VND' => 23000.00,

            // EUR to other currencies
            'EUR_USD' => 1.18,
            'EUR_IDR' => 17650.00,
            'EUR_GBP' => 0.86,
            'EUR_JPY' => 129.00,
            'EUR_SGD' => 1.59,
            'EUR_MYR' => 4.94,
            'EUR_THB' => 38.80,
            'EUR_CAD' => 1.47,
            'EUR_AUD' => 1.65,
            'EUR_CHF' => 1.08,
            'EUR_CNY' => 7.59,
            'EUR_HKD' => 9.20,
            'EUR_KRW' => 1390.00,
            'EUR_NZD' => 1.71,
            'EUR_INR' => 88.50,
            'EUR_PHP' => 59.00,
            'EUR_VND' => 27100.00,

            // Add more currency pairs as needed
            'GBP_USD' => 1.37,
            'GBP_EUR' => 1.16,
            'GBP_IDR' => 20500.00,
            'GBP_JPY' => 150.00,
            'GBP_SGD' => 1.85,
            'GBP_MYR' => 5.75,
            'GBP_THB' => 45.20,
            'GBP_CAD' => 1.71,
            'GBP_AUD' => 1.92,
            'GBP_CHF' => 1.26,
            'GBP_CNY' => 8.84,
            'GBP_HKD' => 10.70,
            'GBP_KRW' => 1615.00,
            'GBP_NZD' => 1.99,
            'GBP_INR' => 103.00,
            'GBP_PHP' => 68.50,
            'GBP_VND' => 31500.00,

            // JPY to major currencies
            'JPY_USD' => 0.0091,
            'JPY_EUR' => 0.0077,
            'JPY_GBP' => 0.0067,
            'JPY_IDR' => 136.50,
            'JPY_SGD' => 0.0123,
            'JPY_MYR' => 0.0382,
            'JPY_THB' => 0.30,
            'JPY_CAD' => 0.0114,
            'JPY_AUD' => 0.0127,
            'JPY_CHF' => 0.0084,
            'JPY_CNY' => 0.0586,
            'JPY_HKD' => 0.0709,
            'JPY_KRW' => 10.73,
            'JPY_NZD' => 0.0132,
            'JPY_INR' => 0.68,
            'JPY_PHP' => 0.45,
            'JPY_VND' => 209.00,

            // SGD to major currencies
            'SGD_USD' => 0.74,
            'SGD_EUR' => 0.63,
            'SGD_GBP' => 0.54,
            'SGD_JPY' => 81.30,
            'SGD_IDR' => 11100.00,
            'SGD_MYR' => 3.11,
            'SGD_THB' => 24.44,
            'SGD_CAD' => 0.93,
            'SGD_AUD' => 1.04,
            'SGD_CHF' => 0.68,
            'SGD_CNY' => 4.78,
            'SGD_HKD' => 5.78,
            'SGD_KRW' => 874.00,
            'SGD_NZD' => 1.07,
            'SGD_INR' => 55.56,
            'SGD_PHP' => 37.04,
            'SGD_VND' => 17037.00,

            // Add reverse rates for better coverage
            'IDR_USD' => 0.000067,
            'IDR_EUR' => 0.000057,
            'IDR_GBP' => 0.000049,
            'IDR_JPY' => 0.0073,
            'IDR_SGD' => 0.000090,
            'IDR_MYR' => 0.00028,
            'IDR_THB' => 0.0022,
            'IDR_CAD' => 0.000083,
            'IDR_AUD' => 0.000093,
            'IDR_CHF' => 0.000061,
            'IDR_CNY' => 0.00043,
            'IDR_HKD' => 0.00052,
            'IDR_KRW' => 0.079,
            'IDR_NZD' => 0.000097,
            'IDR_INR' => 0.005,
            'IDR_PHP' => 0.0033,
            'IDR_VND' => 1.53,
        ];
    }

    /**
     * Get all supported currencies.
     */
    public function getSupportedCurrencies(): array
    {
        return [
            'IDR', 'USD', 'EUR', 'GBP', 'JPY', 'SGD', 'MYR', 'THB',
            'CAD', 'AUD', 'CHF', 'CNY', 'HKD', 'KRW', 'NZD', 'INR', 'PHP', 'VND'
        ];
    }

    /**
     * Check if conversion is supported between two currencies.
     */
    public function isConversionSupported(string $fromCurrency, string $toCurrency): bool
    {
        if ($fromCurrency === $toCurrency) {
            return true;
        }

        return $this->getRate($fromCurrency, $toCurrency) !== null;
    }

    /**
     * Get formatted conversion result.
     */
    public function getFormattedConversion(float $amount, string $fromCurrency, string $toCurrency): ?array
    {
        $convertedAmount = $this->convert($amount, $fromCurrency, $toCurrency);
        
        if ($convertedAmount === null) {
            return null;
        }

        return [
            'original_amount' => $amount,
            'original_currency' => $fromCurrency,
            'converted_amount' => $convertedAmount,
            'converted_currency' => $toCurrency,
            'rate' => $this->getRate($fromCurrency, $toCurrency),
        ];
    }
}
