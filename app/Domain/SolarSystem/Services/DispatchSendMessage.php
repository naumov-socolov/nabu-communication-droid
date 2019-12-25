<?php

namespace App\Domain\SolarSystem\Services;

use App\Domain\SolarSystem\Events\SolarSystemRequestSent;
use App\Domain\SolarSystem\Jobs\SendMessage;
use App\Domain\SolarSystem\Models\Message;
use App\Domain\SolarSystem\Models\SolarSystem;
use Illuminate\Http\Request;

class DispatchSendMessage
{
    const QUEUE_NAME = 'solar_system';

    public function handle(Request $request, SolarSystem $solarSystem): Message
    {
        $message = resolve(InitMessage::class)
            ->handle(
                auth()->user(),
                $solarSystem,
                $request->get('amount')
            );

        SendMessage::dispatch(
            $message,
            ['ip' => $request->ip()]
        )->onQueue(self::QUEUE_NAME);

        event(new SolarSystemRequestSent($message));

        return $message;
    }
}
