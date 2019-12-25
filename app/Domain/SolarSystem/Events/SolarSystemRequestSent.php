<?php

namespace App\Domain\SolarSystem\Events;

use App\Domain\SolarSystem\Models\Message;
use Illuminate\Queue\SerializesModels;

class SolarSystemRequestSent
{
    use SerializesModels;

    public $message;

    /**
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }
}
