<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    \App\Models\User::factory(10)->create();
    \App\Models\Hotel::factory(5)->create();
    \App\Models\Restaurant::factory(5)->create();
    \App\Models\Cruise::factory(5)->create();
    \App\Models\Activity::factory(5)->create();
    \App\Models\Trip::factory(5)->create();
    \App\Models\Car::factory(5)->create();

    \App\Models\Image::factory(10)->create();
    \App\Models\Favorite::factory(10)->create();
    \App\Models\Cart::factory(10)->create();
    $this->call([
        FlightSeeder::class,
    ]);

}
}