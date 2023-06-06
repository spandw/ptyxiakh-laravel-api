<?php

namespace Database\Factories;

use App\Models\ParkingSpot;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParkingSpotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'city' => $this->faker->randomElement(['Thessaloniki', 'Athina', 'Lamia', 'Volos']),
            'address' => $this->faker->address(),
            'title' => $this->faker->text(20),
            'description' => $this->faker->text(128),
            'vehicle_type' =>  $this->faker->randomElement(['motorbike', 'car', 'suv', 'truck']),
            'price' => random_int(1, 99),


        ];
    }
}
