<?php

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
        $seeds = [
            UserSeeder::class,
            SolarSystemSeeder::class,
            MessageSeeder::class,
        ];

        $this->call($seeds);
    }
}
