<?php

use App\Domain\SolarSystem\Models\Message;
use App\Domain\SolarSystem\Models\RequestLog;
use App\Domain\SolarSystem\Models\SolarSystem;
use App\Domain\Users\Models\User;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run()
    {
        factory(Message::class)->create(
            [
                'user_id' => User::all()->first()->id,
                'solar_system_id' => SolarSystem::all()->first()->id,
            ]
        )->each(
            function($message) {
                factory(RequestLog::class)->create(
                    [
                        'message_id' => $message->id,
                    ]
                );
            }
        );
    }
}
