<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
            'name_en' => $this->faker->sentence(3),
            'name_ar' => 'نشاط ' . $this->faker->word,
            'description_en' => $this->faker->paragraph,
            'description_ar' => $this->faker->paragraph,
            'location' => $this->faker->city,
            'booking_link' => $this->faker->url,
            'price' => $this->faker->randomFloat(2, 50, 500),
            'rate' => $this->faker->randomFloat(1, 0, 5),
            'duration' => $this->faker->numberBetween(1, 5) . ' hours',
            'type' => $this->faker->randomElement(['indoor', 'outdoor']),
        ];
}

}
