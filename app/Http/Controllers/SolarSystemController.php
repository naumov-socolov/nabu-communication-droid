<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Domain\SolarSystem\Models\SolarSystem;

class SolarSystemController extends BaseController
{
    public function index(SolarSystem $solarSystem)
    {
        dump('show solar systems', $solarSystem->all());

        // todo create SolarSystemResource -> get latest message status
        // todo return SolarSystemResource::collection
    }
}
