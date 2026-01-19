<?php

namespace Database\Factories;

use App\Models\Flight;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlightFactory extends Factory
{
    protected $model = Flight::class;

    public function definition()
    {
        $departure = $this->faker->dateTimeBetween('+1 days', '+10 days');
        $arrival = (clone $departure)->modify('+'.rand(2, 6).' hours');
        $returnDeparture = (clone $arrival)->modify('+'.rand(1, 3).' days');
        $returnArrival = (clone $returnDeparture)->modify('+'.rand(2, 6).' hours');

        $stops = $this->faker->randomElements([
            'Dubai', 'Doha', 'Frankfurt', 'Rome', 'Cairo', 'Istanbul'
        ], rand(0, 3));

        return [
            'style' => $this->faker->randomElement(['Economy', 'Business', 'Luxury']),
            'from_location' => $this->faker->city,
            'departure_time' => $departure,
            'to_location' => $this->faker->city,
            'arrival_time' => $arrival,
            'return_from' => $this->faker->boolean ? $this->faker->city : null,
            'return_departure_time' => $this->faker->boolean ? $returnDeparture : null,
            'return_to' => $this->faker->boolean ? $this->faker->city : null,
            'return_arrival_time' => $this->faker->boolean ? $returnArrival : null,
            'stops_count' => count($stops),
            'stop_locations' => $stops,
            'duration' => round($this->faker->randomFloat(1, 2, 12), 1),
            'price' => $this->faker->randomFloat(2, 100, 2000),
            'rate' => $this->faker->randomFloat(1, 3, 5),
            'booking_link' => $this->faker->url,
        ];
    }
}

