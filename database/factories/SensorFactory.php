<?php

namespace Database\Factories;

use App\Models\Sensor;
use Illuminate\Database\Eloquent\Factories\Factory;

class SensorFactory extends Factory
{
    protected $model = Sensor::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word() . ' Sensor',
            'location' => $this->faker->city,
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
