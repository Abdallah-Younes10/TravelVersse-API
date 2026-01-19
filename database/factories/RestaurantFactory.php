<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name_en' => $this->faker->company,
            'name_ar' => 'مطعم ' . $this->faker->word,
            'description_en' => $this->faker->paragraph,
            'description_ar' => $this->faker->paragraph,
            'location' => $this->faker->city,
            'booking_link' => $this->faker->url,
            'rate' => $this->faker->randomFloat(1, 0, 5),
            'property_type' => $this->faker->randomElement(['cafe', 'diner', 'restaurant']),
            'cuisine' => $this->faker->randomElement(['Italian', 'Chinese', 'Egyptian']),
            'features' => implode(', ', $this->faker->words(3)),
        ];
    }
}
