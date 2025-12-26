<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Call your seeders here
        $this->call([
            ServicesSeeder::class,
            UsersSeeder::class, // Optional: only if you want test users
            // Add more seeders here as you create them
        ]);
    }
}