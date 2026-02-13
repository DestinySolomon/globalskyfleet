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
        // Add missing columns to chat_messages if they don't exist
        Schema::table('chat_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('chat_messages', 'sender_name')) {
                $table->string('sender_name')->nullable()->after('sender_type');
            }
            
            if (!Schema::hasColumn('chat_messages', 'is_admin')) {
                $table->boolean('is_admin')->default(false)->after('is_read');
            }
        });

        // Add missing columns to chat_conversations if they don't exist
        Schema::table('chat_conversations', function (Blueprint $table) {
            if (!Schema::hasColumn('chat_conversations', 'name')) {
                $table->string('name')->nullable()->after('user_id');
            }
            
            if (!Schema::hasColumn('chat_conversations', 'email')) {
                $table->string('email')->nullable()->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            if (Schema::hasColumn('chat_messages', 'sender_name')) {
                $table->dropColumn('sender_name');
            }
            
            if (Schema::hasColumn('chat_messages', 'is_admin')) {
                $table->dropColumn('is_admin');
            }
        });

        Schema::table('chat_conversations', function (Blueprint $table) {
            if (Schema::hasColumn('chat_conversations', 'name')) {
                $table->dropColumn('name');
            }
            
            if (Schema::hasColumn('chat_conversations', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};
