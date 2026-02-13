<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Update service weight limits
        DB::table('services')->where('code', 'EXP')->update(['min_weight' => 0.1, 'max_weight' => 50]);
        DB::table('services')->where('code', 'ECO')->update(['min_weight' => 0.1, 'max_weight' => 100]);
        DB::table('services')->where('code', 'FRT')->update(['min_weight' => 10, 'max_weight' => 2000]);
        DB::table('services')->where('code', 'DOC')->update(['min_weight' => 0.1, 'max_weight' => 5]);
    }

    public function down()
    {
        // Reset to default
        DB::table('services')->update(['min_weight' => 0.1]);
    }
};
