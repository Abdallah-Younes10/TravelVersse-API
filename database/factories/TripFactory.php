<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
            'name_en' => $this->faker->sentence,
            'name_ar' => 'رحلة ' . $this->faker->word,
            'location' => $this->faker->city,
            'price' => $this->faker->randomFloat(2, 1000, 5000),
            'start_date' => now(),
            'end_date' => now()->addDays(3),
            'guide_id' => User::factory(),
            'booking_link' => $this->faker->url,
            'rate' => $this->faker->randomFloat(1, 0, 5),
            'duration' => $this->faker->numberBetween(1, 14) . ' days',
            'continent' => $this->faker->randomElement(['Africa', 'Asia', 'Europe', 'America']),
            'difficulty' => $this->faker->randomElement(['easy', 'moderate', 'hard']),
        ];

}

}
