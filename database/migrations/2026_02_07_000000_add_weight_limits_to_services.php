<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            // Add min_weight if it doesn't exist
            if (!Schema::hasColumn('services', 'min_weight')) {
                $table->decimal('min_weight', 8, 3)->default(0.1)->after('code');
            }
        });
    }

    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'min_weight')) {
                $table->dropColumn('min_weight');
            }
        });
    }
};
