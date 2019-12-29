<?php

namespace App\Http\Controllers;

use App\Domain\SolarSystem\Resources\SolarSystem as SolarSystemResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use App\Domain\SolarSystem\Models\SolarSystem;

class SolarSystemController extends BaseController
{
    public function index(SolarSystem $solarSystem)
    {
        return
            SolarSystemResource::collection($solarSystem->all())
                ->response()
                ->setStatusCode(JsonResponse::HTTP_OK);
    }
}
