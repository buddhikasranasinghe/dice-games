<?php

namespace Tests\Feature\Ludo;

use App\Models\Game;
use Tests\TestCase;
use App\Models\User;
use Domain\Ludo\Enums\GameStatus;
use Illuminate\Testing\TestResponse;
use Domain\Ludo\Enums\PlayerSelectionMode;

class CreateGameTest extends TestCase
{
    protected User $challenger;

    public function setUp(): void
    {
        parent::setUp();

        $this->challenger = User::factory()
            ->create();

        $this->signIn($this->challenger);
    }

    /**
     * @test
     * @dataProvider invalidGamePayloadProvider
     */
    public function when_invalid_payload_give(array $payload, array $errors)
    {
        $response = $this->createGame($payload);

        $this->assertNotAbleToCreateGame($response, $errors);
    }

    /** @test */
    public function when_valid_payload_given()
    {
        $payload = [
            'settings' => [
                'player_selection_mode' => PlayerSelectionMode::AUTOMATICALLY,
                'total_players' => 2,
            ]
        ];

        $response = $this->createGame($payload);

        $this->assertGameCreated($response);
    }

    /** @test */
    public function when_challenger_already_start_another_game()
    {
        Game::factory()
            ->forPlayer($this->challenger)
            ->create();

        $payload = [
            'settings' => [
                'player_selection_mode' => PlayerSelectionMode::AUTOMATICALLY,
                'total_players' => 2,
            ]
        ];

        $response = $this->createGame($payload);

        $response->assertUnprocessable();
    }

    protected function createGame(array $payload): TestResponse
    {
        return $this->postJson('api/game', $payload);
    }

    public static function invalidGamePayloadProvider(): array
    {
        return [
            [
                [
                    'challenger' => 'challenger',
                    'settings' => [
                        'player_selection_mode' => '',
                        'total_players' => 2,
                    ]
                ],
                [
                    'settings.player_selection_mode' => [
                        'The settings.player selection mode field is required.'
                    ]
                ]
            ],
            [
                [
                    'challenger' => 'challenger',
                    'settings' => [
                        'player_selection_mode' => 'some value',
                        'total_players' => 2,
                    ]
                ],
                [
                    'settings.player_selection_mode' => [
                        'The settings.player selection mode field must be `auto` or `manual`.'
                    ]
                ]
            ],
            [
                [
                    'challenger' => 'challenger',
                    'settings' => [
                        'player_selection_mode' => PlayerSelectionMode::AUTOMATICALLY,
                        'total_players' => '',
                    ]
                ],
                [
                    'settings.total_players' => [
                        'The settings.total players field is required.'
                    ]
                ]
            ],
            [
                [
                    'challenger' => 'challenger',
                    'settings' => [
                        'player_selection_mode' => PlayerSelectionMode::AUTOMATICALLY,
                        'total_players' => 'some value',
                    ]
                ],
                [
                    'settings.total_players' => [
                        'The settings.total players field must be a number.',
                        'The settings.total players field should be 2 or 4.'
                    ]
                ]
            ],
            [
                [
                    'challenger' => 'challenger',
                    'settings' => [
                        'player_selection_mode' => PlayerSelectionMode::AUTOMATICALLY,
                        'total_players' => 3,
                    ]
                ],
                [
                    'settings.total_players' => [
                        'The settings.total players field should be 2 or 4.'
                    ]
                ]
            ],
        ];
    }

    protected function assertNotAbleToCreateGame(TestResponse $response, array $errors): void
    {
        $response->assertUnprocessable();

        $this->assertEquals(
            [
                'message' => 'Oops... we encountered an issue.',
                'errors' => $errors
            ],
            $response->json()
        );
    }

    protected function assertGameCreated(TestResponse $response): void
    {
        $response->assertCreated();

        $expectedGame = [
            'challenger_id' => $this->challenger->getKey(),
            'settings' => [
                "playerSelectionMode" => "auto",
                "totalPlayers" => 2
            ],
            'status' => GameStatus::OPENNED->value,
            'type' => 'Ludo'
        ];

        $this->assertExpectedGameCreated($response, $expectedGame);
    }

    protected function assertExpectedGameCreated(TestResponse $response, array $expectedGame): void
    {
        $this->assertEquals(
            $expectedGame,
            [
                'challenger_id' => $response->json('game')['challenger_id'],
                'settings' => $response->json('game')['settings'],
                'status' => $response->json('game')['status'],
                'type' => $response->json('game')['type']
            ]
        );
    }
}
