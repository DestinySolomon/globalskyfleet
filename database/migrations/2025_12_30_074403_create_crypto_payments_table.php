<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crypto_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('crypto_type', ['BTC', 'USDT_ERC20', 'USDT_TRC20']);
            $table->decimal('crypto_amount', 18, 8)->nullable(); // For BTC
            $table->decimal('usdt_amount', 15, 6)->nullable(); // For USDT
            $table->string('payment_address');
            $table->string('transaction_id')->nullable();
            $table->string('payment_proof')->nullable(); // Path to uploaded screenshot
            $table->enum('status', ['pending', 'processing', 'confirmed', 'completed', 'failed', 'expired'])->default('pending');
            $table->integer('confirmations')->default(0);
            $table->decimal('exchange_rate', 15, 8)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            $table->index(['invoice_id', 'status']);
            $table->index('transaction_id');
            $table->index('payment_address');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crypto_payments');
    }
};