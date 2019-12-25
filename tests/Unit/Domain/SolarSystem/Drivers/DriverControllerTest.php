<?php

namespace Tests\Unit\Domain\SolarSystem\Drivers;

use App\Domain\SolarSystem\Contracts\Services\RequestStrategyContract;
use App\Domain\SolarSystem\Drivers\Eriadu\EriaduController;
use App\Domain\SolarSystem\Events\SolarSystemRequestReceived;
use App\Domain\SolarSystem\Models\Message;
use App\Domain\SolarSystem\Services\RequestStrategies\FastApprovalStrategy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class DriverControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithoutEvents;
    use WithFaker;

    public function test_handles_request_strategy_and_fires_event()
    {
        Queue::fake();

        $mock = $this->mock(
            RequestStrategyContract::class,
            function ($mock) {
                $mock->shouldReceive('assignRequestHandlers')
                    ->andReturn($mock);
                $mock->shouldReceive('handle')->once();
            }
        );

        $this->app->bind(
            FastApprovalStrategy::class,
            function() use ($mock) {
                return $mock;
            }
        );

        resolve(
            EriaduController::class,
            [
                'message' => factory(Message::class)->create(),
                'additionalData' => ['ip' => $this->faker->ipv4()],
            ]
        )->handle();

        Event::assertDispatched(
            SolarSystemRequestReceived::class
        );
    }
}
