<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ExchangeRate extends Model
{
    protected $fillable = [
        'from_currency',
        'to_currency',
        'rate',
        'fetched_at',
    ];

    protected $casts = [
        'rate' => 'decimal:8',
        'fetched_at' => 'datetime',
    ];

    /**
     * Get latest rate for a cryptocurrency
     */
    public static function getLatestRate(string $cryptoType): ?float
    {
        $cacheKey = "exchange_rate_{$cryptoType}";
        
        return Cache::remember($cacheKey, 300, function () use ($cryptoType) {
            $rate = self::where('to_currency', $cryptoType)
                ->latest('fetched_at')
                ->first();
            
            if ($rate) {
                return (float) $rate->rate;
            }
            
            // If no rate in DB, fetch fresh using service
            $service = app(\App\Services\ExchangeRateService::class);
            
            return match($cryptoType) {
                'BTC' => $service->getBtcRate(),
                'USDT' => $service->getUsdtRate(),
                default => null,
            };
        });
    }

    /**
     * Get USD value of crypto amount
     */
    public static function convertToUsd(float $cryptoAmount, string $cryptoType): ?float
    {
        $rate = self::getLatestRate($cryptoType);
        
        if (!$rate) {
            return null;
        }

        return $cryptoAmount * $rate;
    }
}