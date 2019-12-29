<?php

use App\Domain\SolarSystem\Models\SolarSystem;
use Illuminate\Database\Seeder;

class SolarSystemSeeder extends Seeder
{
    public function run()
    {
        factory(SolarSystem::class)->create(
            [
                'title' => 'Eriadu',
                'driver_name' => 'eriadu',
                'img' => 'https://res.cloudinary.com/drmyhljip/image/upload/v1577627240/nabu_communication_droid/solar_systems/eriadu_hbmovh.svg',
            ]
        );

        factory(SolarSystem::class)->create(
            [
                'title' => 'Corellia',
                'driver_name' => 'corellia',
                'img' => 'https://res.cloudinary.com/drmyhljip/image/upload/v1577627240/nabu_communication_droid/solar_systems/corellia_maviol.svg',
            ]
        );
    }
}
