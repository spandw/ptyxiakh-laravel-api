<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\ParkingSpot::factory(25)->create();
         //\App\Models\Reservation::factory(10)->create();
    }
}
