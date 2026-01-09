<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->json('data');
            $table->timestamp('read_at')->nullable();
            
            // Custom columns for courier system
            $table->string('category')->default('shipment'); // shipment, payment, system
            $table->string('priority')->default('normal'); // low, normal, high, urgent
            
            // CHANGE THESE 2 LINES:
            $table->string('shipment_id')->nullable(); // Changed from foreignId()
            $table->string('invoice_id')->nullable(); // Changed from foreignId()
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['notifiable_id', 'notifiable_type', 'read_at']);
            $table->index(['category', 'priority']);
            $table->index('shipment_id');
            $table->index('invoice_id');
            
            // Add foreign keys separately if needed (optional)
            // $table->foreign('shipment_id')->references('id')->on('shipments')->onDelete('set null');
            // $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};