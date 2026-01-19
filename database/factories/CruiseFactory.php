<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CruiseFactory extends Factory
{
    public function definition(): array
{
    return [
            'name_en' => $this->faker->company,
            'name_ar' => 'كروز ' . $this->faker->word,
            'description_en' => $this->faker->paragraph,
            'description_ar' => $this->faker->paragraph,
            'location' => $this->faker->city,
            'booking_link' => $this->faker->url,
            'price' => $this->faker->randomFloat(2, 100, 1000),
            'rate' => $this->faker->randomFloat(1, 0, 5),
            'from' => $this->faker->city,
            'to' => $this->faker->city,
            'depart_time' => $this->faker->time,
            'return_time' => $this->faker->time,
            'property_type' => $this->faker->randomElement(['standard', 'premium']),
            'amenities' => implode(', ', $this->faker->words(3)),
            'style' => $this->faker->randomElement(['classic', 'modern']),
        ];
}

}
