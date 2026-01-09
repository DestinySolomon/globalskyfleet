<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExchangeRate;
use Carbon\Carbon;

class ExchangeCleanup extends Command
{
    protected $signature = 'exchange:cleanup';
    protected $description = 'Clean up old exchange rate records';

    public function handle()
    {
        $this->info('🧹 Cleaning up old exchange rate records...');
        
        $cutoffDate = Carbon::now()->subDays(7);
        $deleted = ExchangeRate::where('created_at', '<', $cutoffDate)->delete();
        
        $this->info("✅ Deleted {$deleted} old exchange rate records (older than 7 days)");
        
        return Command::SUCCESS;
    }
}