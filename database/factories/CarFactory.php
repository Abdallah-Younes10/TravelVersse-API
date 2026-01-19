<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory
{
    public function definition(): array
    {
        return [
            'brand' => $this->faker->company,
            'model' => $this->faker->word,
            'type' => $this->faker->randomElement(['SUV', 'Sedan', 'Hatchback']),
            'year' => $this->faker->year,
            'price' => $this->faker->randomFloat(2, 50, 500),
            'location' => $this->faker->city,
            'description_en' => $this->faker->paragraph,
            'description_ar' => $this->faker->paragraph,
            'booking_link' => $this->faker->url,
            'rate' => $this->faker->randomFloat(1, 0, 5),
            'seats' => $this->faker->numberBetween(2, 7),
            'large_bag' => $this->faker->numberBetween(0, 3),
            'small_bag' => $this->faker->numberBetween(0, 4),
            // 'specs' => implode(', ', $this->faker->words(3)),
            'supplier' => $this->faker->company,
        ];
    }
}
