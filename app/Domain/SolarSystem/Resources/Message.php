<?php

namespace App\Domain\SolarSystem\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Message extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'updated_at' => $this->updated_at->format("Y-m-d H:i:s"),
        ];
    }
}
