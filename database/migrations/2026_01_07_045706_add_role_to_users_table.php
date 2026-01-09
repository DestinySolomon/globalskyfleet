<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'admin'])->default('user')->after('email');
        });
        
        // Update existing admin emails to have admin role
        DB::table('users')
            ->where('email', 'LIKE', '%admin%')
            ->orWhere('email', 'admin@globalskyfleet.com')
            ->orWhere('email', 'admin@gmail.com')
            ->update(['role' => 'admin']);
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};