<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crypto_addresses', function (Blueprint $table) {
            $table->id();
            $table->enum('crypto_type', ['BTC', 'USDT_ERC20', 'USDT_TRC20']);
            $table->string('address');
            $table->string('label')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('usage_count')->default(0);
            $table->foreignId('created_by')->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['crypto_type', 'address']);
            $table->index(['crypto_type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crypto_addresses');
    }
};