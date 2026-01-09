<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('from_currency')->default('USD');
            $table->string('to_currency'); // BTC, USDT
            $table->decimal('rate', 15, 8);
            $table->timestamp('fetched_at');
            $table->timestamps();
            
            $table->index(['to_currency', 'fetched_at']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};