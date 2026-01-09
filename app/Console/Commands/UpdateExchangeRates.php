<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ExchangeRateService;

class UpdateExchangeRates extends Command
{
    protected $signature = 'exchange:update';
    protected $description = 'Update cryptocurrency exchange rates from CoinGecko';

    public function handle()
    {
        $this->info('Updating exchange rates...');
        
        $service = new ExchangeRateService();
        $rates = $service->updateRates();
        
        $this->info('✅ Exchange rates updated successfully!');
        $this->line('');
        $this->info('Current Rates:');
        $this->table(
            ['Cryptocurrency', 'Rate (USD)', 'Last Updated'],
            [
                ['Bitcoin (BTC)', '$' . number_format($rates['BTC'], 2), now()->format('Y-m-d H:i:s')],
                ['Tether (USDT)', '$' . number_format($rates['USDT'], 4), now()->format('Y-m-d H:i:s')],
            ]
        );
        
        // Display example conversions
        $this->line('');
        $this->info('Example Conversions:');
        $this->line('$100 USD = ' . number_format(100 / $rates['BTC'], 8) . ' BTC');
        $this->line('$100 USD = ' . number_format(100 / $rates['USDT'], 2) . ' USDT');
        
        return Command::SUCCESS;
    }
}