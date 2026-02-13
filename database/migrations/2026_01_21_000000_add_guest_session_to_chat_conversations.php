<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('chat_conversations', function (Blueprint $table) {
            if (!Schema::hasColumn('chat_conversations', 'guest_session_id')) {
                $table->string('guest_session_id')->nullable()->unique()->after('user_id');
            }
        });
    }

    public function down()
    {
        Schema::table('chat_conversations', function (Blueprint $table) {
            if (Schema::hasColumn('chat_conversations', 'guest_session_id')) {
                $table->dropColumn('guest_session_id');
            }
        });
    }
};
