<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    public function run()
    {
        $services = [
            [
                'code' => 'EXP',
                'name' => 'Express',
                'description' => 'Fastest delivery option for urgent shipments',
                'is_international' => true,
                'max_weight' => 30,
                'max_dimensions' => json_encode(['length' => 120, 'width' => 80, 'height' => 80, 'unit' => 'cm']),
                'transit_time_min' => 2,
                'transit_time_max' => 5,
                'is_active' => true,
            ],
            [
                'code' => 'ECO',
                'name' => 'Economy',
                'description' => 'Cost-effective shipping for non-urgent deliveries',
                'is_international' => true,
                'max_weight' => 50,
                'max_dimensions' => json_encode(['length' => 150, 'width' => 100, 'height' => 100, 'unit' => 'cm']),
                'transit_time_min' => 5,
                'transit_time_max' => 10,
                'is_active' => true,
            ],
            [
                'code' => 'FRT',
                'name' => 'Freight',
                'description' => 'Heavy cargo and bulk shipments',
                'is_international' => true,
                'max_weight' => 1000,
                'max_dimensions' => null,
                'transit_time_min' => 7,
                'transit_time_max' => 14,
                'is_active' => true,
            ],
            [
                'code' => 'DOC',
                'name' => 'Documents',
                'description' => 'Secure document delivery service',
                'is_international' => true,
                'max_weight' => 2,
                'max_dimensions' => json_encode(['length' => 40, 'width' => 30, 'height' => 5, 'unit' => 'cm']),
                'transit_time_min' => 3,
                'transit_time_max' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        $this->command->info('Services seeded successfully!');
    }
}