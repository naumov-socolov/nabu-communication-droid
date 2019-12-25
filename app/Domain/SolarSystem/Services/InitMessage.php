<?php

namespace App\Domain\SolarSystem\Services;

use App\Domain\SolarSystem\Models\Message;
use App\Domain\SolarSystem\Models\SolarSystem;
use App\Domain\Users\Models\User;

class InitMessage
{
    /**
     * @param User        $user
     * @param SolarSystem $solarSystem
     * @param int         $amount
     * @return Message
     */
    public function handle(User $user, SolarSystem $solarSystem, int $amount): Message
    {
        return Message::create([
            'user_id' => $user->id,
            'solar_system_id' => $solarSystem->id,
            'status' => Message::WAITING_QUEUE,
            'amount' => $amount,
            'expired_at' => now()->addMinute(),
        ]);
    }
}
