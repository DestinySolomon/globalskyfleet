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
        Schema::table('documents', function (Blueprint $table) {
            // Add status column after description column
            $table->string('status')
                  ->default('pending')
                  ->after('description')
                  ->comment('Document status: pending, approved, rejected, etc.');
            
            // Optional: Add index for better performance when querying by status
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Remove the status column and its index when rolling back
            $table->dropIndex(['status']);
            $table->dropColumn('status');
        });
    }
};