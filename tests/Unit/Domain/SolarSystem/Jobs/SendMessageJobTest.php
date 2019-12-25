<?php

namespace Tests\Unit\Domain\SolarSystem\Jobs;

use App\Domain\SolarSystem\Contracts\DriverControllerContract;
use App\Domain\SolarSystem\Jobs\SendMessage;
use App\Domain\SolarSystem\Models\Message;
use App\Domain\SolarSystem\Models\SolarSystem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Mockery;
use Tests\TestCase;

class SendMessageJobTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_message_sent_to_waiting_and_handles_correct_driver_controller()
    {
        $message = factory(Message::class)->create(
            [
                'solar_system_id' => factory(SolarSystem::class)->create(
                    ['driver_name' => 'eriadu']
                )->id,
                'status' => '',
            ]
        );

        $additionalData = ['ip' => $this->faker->ipv4];

        $mock = Mockery::mock(DriverControllerContract::class);
        $mock->shouldReceive('handle')->once();

        $this->app->bind(
            Config::get('solar_systems.eriadu.driver_class'),
            function () use ($mock) {
                return $mock;
            }
        );

        (new SendMessage(
            $message,
            $additionalData
        ))->handle();

        $this->assertEquals(Message::WAITING, $message->refresh()->status);
    }
}
