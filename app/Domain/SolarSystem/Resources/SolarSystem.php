<?php

namespace App\Domain\SolarSystem\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SolarSystem extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'region' => $this->region,
            'sector' => $this->sector,
            'suns' => $this->suns,
            'grid_coordinates' => $this->grid_coordinates,
            'trade_routes' => $this->trade_routes,
            'img' => $this->img,
        ];
    }
}
