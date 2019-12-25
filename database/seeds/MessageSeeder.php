<?php

use App\Domain\SolarSystem\Models\Message;
use App\Domain\SolarSystem\Models\RequestLog;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run()
    {
        factory(Message::class)->create()->each(
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
