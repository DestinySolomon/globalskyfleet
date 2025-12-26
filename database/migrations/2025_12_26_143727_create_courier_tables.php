<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Services table first (no dependencies)
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->boolean('is_international')->default(false);
            $table->decimal('max_weight', 8, 3)->nullable();
            $table->json('max_dimensions')->nullable();
            $table->integer('transit_time_min')->nullable();
            $table->integer('transit_time_max')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Addresses table (depends on users)
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['home', 'work', 'billing', 'shipping'])->default('shipping');
            $table->string('contact_name');
            $table->string('contact_phone', 20);
            $table->string('company')->nullable();
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('postal_code', 20);
            $table->char('country_code', 2);
            $table->boolean('is_default')->default(false);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'type']);
            $table->index(['country_code', 'city']);
        });

        // 3. Shipments table (depends on services and addresses)
        Schema::create('shipments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tracking_number', 20)->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('restrict');
            $table->foreignId('sender_address_id')->constrained('addresses')->onDelete('restrict');
            $table->foreignId('recipient_address_id')->constrained('addresses')->onDelete('restrict');
            
            $table->enum('status', [
                'pending', 
                'confirmed',
                'picked_up', 
                'in_transit', 
                'customs_hold', 
                'out_for_delivery', 
                'delivered', 
                'cancelled', 
                'returned'
            ])->default('pending');
            
            $table->string('current_location')->nullable();
            $table->decimal('weight', 8, 3);
            $table->json('dimensions')->nullable();
            $table->decimal('declared_value', 12, 2);
            $table->char('currency', 3)->default('USD');
            $table->text('content_description');
            $table->decimal('insurance_amount', 12, 2)->default(0.00);
            $table->boolean('insurance_enabled')->default(false);
            $table->boolean('requires_signature')->default(false);
            $table->boolean('is_dangerous_goods')->default(false);
            $table->text('special_instructions')->nullable();
            $table->timestamp('estimated_delivery')->nullable();
            $table->timestamp('actual_delivery')->nullable();
            $table->timestamp('pickup_date')->nullable();
            $table->timestamps();
            
            $table->index(['tracking_number']);
            $table->index(['user_id', 'created_at']);
            $table->index(['status', 'estimated_delivery']);
        });

        // 4. Shipment Status History (depends on shipments)
        Schema::create('shipment_status_history', function (Blueprint $table) {
            $table->id();
            $table->uuid('shipment_id');
            $table->string('status');
            $table->string('location');
            $table->text('description')->nullable();
            $table->timestamp('scan_datetime');
            $table->timestamps();
            
            $table->foreign('shipment_id')->references('id')->on('shipments')->onDelete('cascade');
            $table->index(['shipment_id', 'scan_datetime']);
        });

        // 5. Payments (depends on shipments and users)
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('shipment_id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->char('currency', 3)->default('USD');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'refunded'])->default('pending');
            $table->enum('payment_method', ['card', 'bank_transfer', 'cash_on_delivery', 'paypal', 'usdt'])->default('usdt');
            $table->string('transaction_id')->nullable();
            $table->string('crypto_address')->nullable();
            $table->string('crypto_amount')->nullable();
            $table->string('transaction_hash')->nullable();
            $table->integer('confirmations')->default(0);
            $table->enum('crypto_status', ['pending', 'confirmed', 'failed'])->nullable();
            $table->json('gateway_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            $table->foreign('shipment_id')->references('id')->on('shipments')->onDelete('cascade');
            $table->index(['status', 'created_at']);
        });

        // 6. Customs Declarations (depends on shipments)
        Schema::create('customs_declarations', function (Blueprint $table) {
            $table->id();
            $table->uuid('shipment_id');
            $table->string('hs_code', 12)->nullable();
            $table->enum('purpose_of_export', ['commercial', 'gift', 'documents', 'return', 'personal'])->default('commercial');
            $table->string('invoice_number')->nullable();
            $table->boolean('certificate_of_origin')->default(false);
            $table->boolean('export_license_required')->default(false);
            $table->string('export_license_number')->nullable();
            $table->timestamps();
            
            $table->foreign('shipment_id')->references('id')->on('shipments')->onDelete('cascade');
            $table->unique(['shipment_id']);
        });

        // 7. Customs Items (depends on customs_declarations)
        Schema::create('customs_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customs_declaration_id')->constrained()->onDelete('cascade');
            $table->string('description', 255);
            $table->integer('quantity');
            $table->decimal('weight', 8, 3);
            $table->decimal('value', 10, 2);
            $table->char('currency', 3)->default('USD');
            $table->char('country_of_origin', 2)->nullable();
            $table->string('hs_code', 12)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        // Drop in reverse order
        Schema::dropIfExists('customs_items');
        Schema::dropIfExists('customs_declarations');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('shipment_status_history');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('services');
    }
};