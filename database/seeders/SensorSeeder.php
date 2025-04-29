<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SensorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sensors')->insert([
            [
                'sensor_id' => Str::uuid(),
                'location' => 'Colombo Fort',
                'latitude' => 6.9330,
                'longitude' => 79.8500,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sensor_id' => Str::uuid(),
                'location' => 'Pettah',
                'latitude' => 6.9344,
                'longitude' => 79.8506,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sensor_id' => Str::uuid(),
                'location' => 'Bambalapitiya',
                'latitude' => 6.8940,
                'longitude' => 79.8550,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sensor_id' => Str::uuid(),
                'location' => 'Wellawatte',
                'latitude' => 6.8800,
                'longitude' => 79.8600,
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sensor_id' => Str::uuid(),
                'location' => 'Maradana',
                'latitude' => 6.9279,
                'longitude' => 79.8612,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sensor_id' => Str::uuid(),
                'location' => 'Dematagoda',
                'latitude' => 6.9338,
                'longitude' => 79.8762,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sensor_id' => Str::uuid(),
                'location' => 'Rajagiriya',
                'latitude' => 6.9112,
                'longitude' => 79.8884,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sensor_id' => Str::uuid(),
                'location' => 'Nugegoda',
                'latitude' => 6.8650,
                'longitude' => 79.8997,
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sensor_id' => Str::uuid(),
                'location' => 'Dehiwala',
                'latitude' => 6.8508,
                'longitude' => 79.8648,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sensor_id' => Str::uuid(),
                'location' => 'Kollupitiya',
                'latitude' => 6.9158,
                'longitude' => 79.8487,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
