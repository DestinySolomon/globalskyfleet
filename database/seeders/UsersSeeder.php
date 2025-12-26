<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // Create test users
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+1234567890',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+0987654321',
            ],
            [
                'name' => 'Business User',
                'email' => 'business@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+1122334455',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $this->command->info('Test users seeded successfully!');
    }
}