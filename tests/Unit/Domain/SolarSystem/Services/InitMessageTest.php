<?php

namespace Tests\Unit\Domain\SolarSystem\Services;

use App\Domain\SolarSystem\Models\Message;
use App\Domain\SolarSystem\Models\SolarSystem;
use App\Domain\SolarSystem\Services\InitMessage;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InitMessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_create_and_return_message()
    {
        $this->actingAs($user = factory(User::class)->create());

        $amount = 50;
        $solarSystem = factory(SolarSystem::class)->create();

        $message = resolve(InitMessage::class)
            ->handle($user, $solarSystem, $amount);

        $this->assertDatabaseHas(
            'messages',
            [
                'solar_system_id' => $solarSystem->id,
                'user_id' => $user->id,
                'amount' => $amount,
            ]
        );

        $this->assertEquals($solarSystem->id, $message->solar_system_id);
        $this->assertEquals($user->id, $message->user_id);
        $this->assertEquals($amount, $message->amount);

        $this->assertEquals(Message::class, get_class($message));
    }
}
