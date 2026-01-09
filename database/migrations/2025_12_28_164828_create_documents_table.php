<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Change this line - shipments.id is char(36) (UUID), so we need to use char(36) here too
            $table->char('shipment_id', 36);
            
            $table->string('type');
            $table->string('name');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('original_name');
            $table->integer('file_size');
            $table->string('mime_type');
            $table->text('description')->nullable();
            $table->timestamps();

            // Add foreign key constraint separately
            $table->foreign('shipment_id')->references('id')->on('shipments')->onDelete('cascade');
            
            $table->index(['user_id', 'type']);
            $table->index('created_at');
            $table->index('shipment_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};