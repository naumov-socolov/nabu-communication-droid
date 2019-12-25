<?php

namespace Tests\Feature;

use App\Domain\SolarSystem\Models\Message;
use App\Domain\SolarSystem\Models\SolarSystem;
use App\Domain\SolarSystem\Services\DispatchSendMessage;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class SendMessageTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs(factory(User::class)->create());
    }

    public function test_successful_response_should_be_200_and_contain_message_id()
    {
        $solarSystem = factory(SolarSystem::class)->create();

        $message = factory(Message::class)->create();
        $this->mock(DispatchSendMessage::class)
            ->shouldReceive()->handle(Request::class, SolarSystem::class)
            ->once()
            ->andReturn($message);

        $this->get(
            route(
                'api.inner.user.solar_system.send_message',
                [
                    'solarSystem' => $solarSystem,
                    'amount' => 50,
                ]
            )
        )
            ->assertStatus(200)
            ->assertJson(
                [
                    'data' => [
                        'id' => $message->id,
                    ],
                ]
            );
    }

    public function test_unresolved_route_fallbacks()
    {
        $this->get('/api/inner/user/solar-system/1/get-message')
            ->assertStatus(404);
    }

    public function test_messaging_only_existing_solar_systems()
    {
        $this->get($this->route())
            ->assertStatus(404);
    }

    public function test_amount_must_be_specified_and_valid()
    {
        $solarSystem = factory(SolarSystem::class)->create();

        $this->get($this->route(['solarSystem' => $solarSystem, 'amount' => 0]))
            ->assertStatus(422)
            ->assertJson(
                [
                    'errors' => [
                        'amount' => [],
                    ],
                ]
            );

        $this->get($this->route(['solarSystem' => $solarSystem, 'amount' => 24]))
            ->assertStatus(422)
            ->assertJson(
                [
                    'errors' => [
                        'amount' => [],
                    ],
                ]
            );

        $this->get($this->route(['solarSystem' => $solarSystem, 'amount' => 55001]))
            ->assertStatus(422)
            ->assertJson(
                [
                    'errors' => [
                        'amount' => [],
                    ],
                ]
            );
    }

    /**
     * @param array $attr
     * @return string
     */
    protected function route(array $attr = []): string
    {
        return route(
            'api.inner.user.solar_system.send_message',
            [
                'solarSystem' => $attr['solarSystem'] ?? 1,
                'amount' => $attr['amount'] ?? 50,
            ]
        );
    }
}
