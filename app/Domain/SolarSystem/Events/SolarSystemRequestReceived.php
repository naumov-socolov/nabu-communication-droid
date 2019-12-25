<?php

namespace App\Domain\SolarSystem\Events;

use App\Domain\SolarSystem\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SolarSystemRequestReceived implements ShouldBroadcast
{
    public $message;

    /**
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]
     */
    public function broadcastOn()
    {
        return new PrivateChannel('User.SolarSystem.Request.' . $this->message->id);
    }

    public function broadcastAs()
    {
        return 'SolarSystemRequestReceived';
    }
}
