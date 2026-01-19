<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hotel>
 */
class HotelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition(): array
{
    return [
            'name_en' => $this->faker->company,
            'name_ar' => 'فندق ' . $this->faker->word,
            'description_en' => $this->faker->paragraph,
            'description_ar' => $this->faker->sentence,
            'location' => $this->faker->city,
            'booking_link' => $this->faker->url,
            'price' => $this->faker->randomFloat(2, 100, 1000),
            'rate' => $this->faker->randomFloat(1, 0, 5),
            'old_price' => $this->faker->randomFloat(2, 100, 1500),
            'class' => $this->faker->randomElement(['3 stars', '4 stars', '5 stars']),
            'style' => $this->faker->randomElement(['luxury', 'budget', 'boutique']),
            'amenities' => implode(', ', $this->faker->words(3)),
        ];
}


}
