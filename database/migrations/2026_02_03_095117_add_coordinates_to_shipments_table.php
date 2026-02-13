<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            // Add latitude and longitude columns
            $table->decimal('latitude', 10, 8)->nullable()->after('current_location');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->timestamp('location_updated_at')->nullable()->after('longitude');
        });
        
        Schema::table('shipment_status_history', function (Blueprint $table) {
            // Add coordinates to history too
            $table->decimal('latitude', 10, 8)->nullable()->after('location');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    public function down()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'location_updated_at']);
        });
        
        Schema::table('shipment_status_history', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};