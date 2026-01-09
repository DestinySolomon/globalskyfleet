<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ExchangeRateService;

class ExchangeCheck extends Command
{
    protected $signature = 'exchange:check';
    protected $description = 'Check connectivity to CoinGecko and report status';

    public function handle()
    {
        $this->info('Checking CoinGecko connectivity...');

        $service = new ExchangeRateService();
        $ok = $service->canFetchLiveRates();

        if ($ok) {
            $this->info('✅ CoinGecko reachable and returning live rates.');
            return Command::SUCCESS;
        }

        $this->error('⛔ Cannot fetch live rates from CoinGecko.');
        $this->line('Check logs and network/SSL configuration.');
        return Command::FAILURE;
    }
}
