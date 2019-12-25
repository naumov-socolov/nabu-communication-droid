<?php

use App\Domain\SolarSystem\Models\SolarSystem;
use Illuminate\Database\Seeder;

class SolarSystemSeeder extends Seeder
{
    public function run()
    {
        factory(SolarSystem::class, 5)->create();
    }
}
