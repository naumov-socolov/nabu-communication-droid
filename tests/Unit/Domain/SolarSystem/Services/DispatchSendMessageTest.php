<?php

namespace Tests\Unit\Domain\SolarSystem\Services;

use App\Domain\SolarSystem\Events\SolarSystemRequestSent;
use App\Domain\SolarSystem\Jobs\SendMessage;
use App\Domain\SolarSystem\Models\Message;
use App\Domain\SolarSystem\Models\SolarSystem;
use App\Domain\SolarSystem\Services\DispatchSendMessage;
use App\Domain\SolarSystem\Services\InitMessage;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class DispatchSendMessageTest extends TestCase
{
    use RefreshDatabase;
    use WithoutEvents;

    public function test_should_init_message()
    {
        Queue::fake();

        $this->actingAs($user = factory(User::class)->create());

        $request = new Request(['amount' => 50]);
        $solarSystem = factory(SolarSystem::class)->create();

        $spy = $this->spy(InitMessage::class);

        resolve(DispatchSendMessage::class)
            ->handle($request, $solarSystem);

        $spy->shouldHaveReceived()->handle($user, $solarSystem, $request->get('amount'));
    }

    public function test_should_dispatch_job()
    {
        $this->setupSendMessage();
        Queue::assertPushedOn(DispatchSendMessage::QUEUE_NAME, SendMessage::class);
    }

    public function test_should_fire_event()
    {
        $this->setupSendMessage();
        Event::assertDispatched(
            SolarSystemRequestSent::class
        );
    }

    public function test_should_receive_message()
    {
        Queue::fake();

        $this->actingAs($user = factory(User::class)->create());
        $message = factory(Message::class)->create();

        $this->mock(InitMessage::class)
            ->shouldReceive('handle')
            ->once()
            ->andReturn($message);

        $resolvedMessage = resolve(DispatchSendMessage::class)
            ->handle(
                new Request(['amount' => 50]),
                factory(SolarSystem::class)->make()
            );

        $this->assertEquals($resolvedMessage, $message);
    }

    protected function setupSendMessage(): void
    {
        Queue::fake();
        $this->actingAs($user = factory(User::class)->create());

        $this->mock(InitMessage::class)
            ->shouldReceive('handle')
            ->once();

        resolve(DispatchSendMessage::class)
            ->handle(
                new Request(['amount' => 50]),
                factory(SolarSystem::class)->make()
            );
    }
}
