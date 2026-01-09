<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;

class ExchangeRateService
{
    /**
     * Cache duration in seconds (5 minutes)
     */
    private $cacheTime = 300;

    /**
     * Get current BTC to USD rate
     */
    public function getBtcRate(): ?float
    {
        return Cache::remember('btc_usd_rate', $this->cacheTime, function () {
            return $this->fetchBtcRate();
        });
    }

    /**
     * Get current USDT to USD rate
     */
    public function getUsdtRate(): ?float
    {
        return Cache::remember('usdt_usd_rate', $this->cacheTime, function () {
            return $this->fetchUsdtRate();
        });
    }

    /**
     * Fetch BTC rate from CoinGecko
     */
    private function fetchBtcRate(): ?float
    {
        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $http = Http::timeout(5);
            // Allow disabling SSL verification only for local/testing environments
            if (env('COINGECKO_DISABLE_SSL_VERIFY', false) && app()->environment(['local','testing'])) {
                $http = $http->withoutVerifying();
            }
            $response = $http->get('https://api.coingecko.com/api/v3/simple/price', [
                'ids' => 'bitcoin',
                'vs_currencies' => 'usd',
            ]);

            if ($response instanceof \Illuminate\Http\Client\Response && $response->successful()) {
                $data = $response->json() ?? [];
                $rate = $data['bitcoin']['usd'] ?? null;

                if ($rate) {
                    Log::info('BTC rate fetched from CoinGecko: ' . $rate);
                    // Update cache timestamp so callers can detect freshness
                    Cache::put('btc_usd_rate_timestamp', now(), $this->cacheTime);
                    return (float) $rate;
                }
            }

            $status = $response instanceof \Illuminate\Http\Client\Response ? $response->status() : null;
            $body = $response instanceof \Illuminate\Http\Client\Response ? $response->body() : null;
            Log::warning('Failed to fetch BTC rate from CoinGecko', ['status' => $status, 'body' => $body]);

            return $this->getFallbackBtcRate();
            
        } catch (\Exception $e) {
            Log::error('Failed to fetch BTC rate: ' . $e->getMessage());
            return $this->getFallbackBtcRate();
        }
    }

    /**
     * Fetch USDT rate from CoinGecko
     */
    private function fetchUsdtRate(): ?float
    {
        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $http = Http::timeout(5);
            // Allow disabling SSL verification only for local/testing environments
            if (env('COINGECKO_DISABLE_SSL_VERIFY', false) && app()->environment(['local','testing'])) {
                $http = $http->withoutVerifying();
            }
            $response = $http->get('https://api.coingecko.com/api/v3/simple/price', [
                'ids' => 'tether',
                'vs_currencies' => 'usd',
            ]);

            if ($response instanceof \Illuminate\Http\Client\Response && $response->successful()) {
                $data = $response->json() ?? [];
                $rate = $data['tether']['usd'] ?? 1.00;

                Log::info('USDT rate fetched from CoinGecko: ' . $rate);
                // Update cache timestamp so callers can detect freshness
                Cache::put('usdt_usd_rate_timestamp', now(), $this->cacheTime);
                return (float) $rate;
            }

            $status = $response instanceof \Illuminate\Http\Client\Response ? $response->status() : null;
            $body = $response instanceof \Illuminate\Http\Client\Response ? $response->body() : null;
            Log::warning('Failed to fetch USDT rate from CoinGecko', ['status' => $status, 'body' => $body]);

            return $this->getFallbackUsdtRate();
            
        } catch (\Exception $e) {
            Log::error('Failed to fetch USDT rate: ' . $e->getMessage());
            return $this->getFallbackUsdtRate();
        }
    }

    /**
     * Get last stored rate from database
     */
    private function getLastStoredRate(string $cryptoType): ?float
    {
        $rate = ExchangeRate::where('to_currency', $cryptoType)
            ->latest('fetched_at')
            ->first();

        return $rate ? (float) $rate->rate : null;
    }

    /**
     * Get both rates and store in database
     */
    public function updateRates(): array
    {
        $btcRate = $this->fetchBtcRate();
        $usdtRate = $this->fetchUsdtRate();

        // Store in database for historical tracking
        if ($btcRate) {
            ExchangeRate::create([
                'from_currency' => 'USD',
                'to_currency' => 'BTC',
                'rate' => $btcRate,
                'fetched_at' => now(),
            ]);
            
            // Update cache
            Cache::put('btc_usd_rate', $btcRate, $this->cacheTime);
            Cache::put('btc_usd_rate_timestamp', now(), $this->cacheTime);
        }

        if ($usdtRate) {
            ExchangeRate::create([
                'from_currency' => 'USD',
                'to_currency' => 'USDT',
                'rate' => $usdtRate,
                'fetched_at' => now(),
            ]);
            
            // Update cache
            Cache::put('usdt_usd_rate', $usdtRate, $this->cacheTime);
            Cache::put('usdt_usd_rate_timestamp', now(), $this->cacheTime);
        }

        return [
            'BTC' => $btcRate,
            'USDT' => $usdtRate,
        ];
    }

    /**
     * Convert USD to crypto
     */
    public function convertToCrypto(float $usdAmount, string $cryptoType): ?float
    {
        $rate = match($cryptoType) {
            'BTC' => $this->getBtcRate(),
            'USDT', 'USDT_ERC20', 'USDT_TRC20' => $this->getUsdtRate(),
            default => null,
        };

        if (!$rate || $rate <= 0) {
            return null;
        }

        return $usdAmount / $rate;
    }

    /**
     * Get all rates at once
     */
    public function getAllRates(): array
    {
        return [
            'BTC' => $this->getBtcRate(),
            'USDT' => $this->getUsdtRate(),
            'updated_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Check whether CoinGecko is reachable and returns live rates
     */
    public function canFetchLiveRates(): bool
    {
        try {
            $http = Http::timeout(5);
            if (env('COINGECKO_DISABLE_SSL_VERIFY', false) && app()->environment(['local','testing'])) {
                $http = $http->withoutVerifying();
            }

            /** @var \Illuminate\Http\Client\Response $response */
            $response = $http->get('https://api.coingecko.com/api/v3/simple/price', [
                'ids' => 'bitcoin,tether',
                'vs_currencies' => 'usd',
            ]);

            if ($response instanceof \Illuminate\Http\Client\Response && $response->successful()) {
                $data = $response->json() ?? [];
                return isset($data['bitcoin']['usd']) && isset($data['tether']['usd']);
            }

            return false;
        } catch (\Exception $e) {
            Log::warning('canFetchLiveRates failed: ' . $e->getMessage());
            return false;
        }
    }



    // Add these methods to your ExchangeRateService class:

/**
 * Get fallback BTC rate from multiple sources
 */
private function getFallbackBtcRate(): float
{
    // Priority 1: Last successful database rate (last 24 hours)
    $recentRate = ExchangeRate::where('to_currency', 'BTC')
        ->where('fetched_at', '>=', now()->subDay())
        ->latest('fetched_at')
        ->first();
    
    if ($recentRate) {
        return (float) $recentRate->rate;
    }
    
    // Priority 2: Environment variable
    $envRate = env('FALLBACK_BTC_RATE');
    if ($envRate && is_numeric($envRate)) {
        return (float) $envRate;
    }
    
    // Priority 3: Hardcoded default
    return 45000.00;
}

/**
 * Get fallback USDT rate from multiple sources
 */
private function getFallbackUsdtRate(): float
{
    // Priority 1: Last successful database rate (last 24 hours)
    $recentRate = ExchangeRate::where('to_currency', 'USDT')
        ->where('fetched_at', '>=', now()->subDay())
        ->latest('fetched_at')
        ->first();
    
    if ($recentRate) {
        return (float) $recentRate->rate;
    }
    
    // Priority 2: Environment variable
    $envRate = env('FALLBACK_USDT_RATE');
    if ($envRate && is_numeric($envRate)) {
        return (float) $envRate;
    }
    
    // Priority 3: Hardcoded default (USDT should be ~1.00)
    return 1.00;
}

/**
 * Check if rates are stale (older than 10 minutes)
 */
public function areRatesStale(): bool
{
    $btcCache = Cache::get('btc_usd_rate_timestamp');
    $usdtCache = Cache::get('usdt_usd_rate_timestamp');
    
    if (!$btcCache || !$usdtCache) {
        return true;
    }
    
    $tenMinutesAgo = now()->subMinutes(10);
    
    return $btcCache < $tenMinutesAgo || $usdtCache < $tenMinutesAgo;
}

/**
 * Force update rates (bypass cache)
 */
public function forceUpdateRates(): array
{
    Cache::forget('btc_usd_rate');
    Cache::forget('usdt_usd_rate');
    Cache::forget('btc_usd_rate_timestamp');
    Cache::forget('usdt_usd_rate_timestamp');
    
    return $this->updateRates();
}
}