<?php

namespace App\Domain\SolarSystem\Events;

use App\Domain\SolarSystem\Models\Message;
use App\Domain\SolarSystem\Models\SolarSystem;
use App\Domain\Users\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SolarSystemDeferredApprovalReceived implements ShouldBroadcast
{
    public $message;

    public $solarSystem;

    private $user;

    /**
     * @param Message     $message
     * @param User        $user
     * @param SolarSystem $solarSystem
     */
    public function __construct(Message $message, User $user, SolarSystem $solarSystem)
    {
        $this->message = $message;
        $this->user = $user;
        $this->solarSystem = ['id' => $solarSystem->id, 'title' => $solarSystem->title];
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]|PrivateChannel
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.User.' . $this->user->id);
    }

    public function broadcastAs()
    {
        return 'SolarSystemDeferredReceived';
    }
}
