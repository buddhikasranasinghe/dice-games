<?php

namespace Tests\Feature\Ludo;

use App\Models\Pawn;
use Tests\TestCase;
use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;

class MovePawnsTest extends TestCase
{
    protected User $player;
    protected Game $game;
    protected Pawn $pawn;

    public function setUp(): void
    {
        parent::setUp();

        $this->player = User::factory()
            ->create();
    }

    /**
     * @test
     * @dataProvider invalidPayloadProvider
     */
    public function when_invalid_payload_given(array $payload, array $validationErrors)
    {
        $this->signIn($this->player);

        $response = $this->movePawns($payload);

        $this->assertNotAllowedToMovePawnsTillInvalidPayload($response, $validationErrors);
    }

    /** @test */
    public function when_no_game_started_for_logging_user()
    {
        $this->signIn($this->player);

        $this->pawn = Pawn::factory()
            ->create();

        $response = $this->movePawns([
            'player_id' => $this->player->getKey(),
            'game_id' => Str::uuid(),
            'pawn_id' => $this->pawn->getKey(),
            'number_of_moves' => 6,
            'is_sent_back' => false
        ]);

        $this->assertNotAbleToMovePawnsUntilPlayerStartAGame($response);
    }

    /** @test */
    public function when_valid_payload_given()
    {
        $this->signIn($this->player);

        $this->game = Game::factory()
            ->forPlayer($this->player)
            ->create();

        $this->pawn = Pawn::factory()
            ->forGame($this->game)
            ->create();

        $response = $this->movePawns([
            'player_id' => $this->player->getKey(),
            'game_id' => $this->game->getKey(),
            'pawn_id' => $this->pawn->getKey(),
            'number_of_moves' => 6,
            'is_sent_back' => false
        ]);

        $this->assertPawnsMoved($response);
    }

    public static function invalidPayloadProvider(): array
    {
        return [
            [
                'payload' => [
                    'player_id' => '',
                    'game_id' => Str::uuid(),
                    'pawn_id' => Str::uuid(),
                    'number_of_moves' => 6,
                    'is_sent_back' => false
                ],
                'validationErrors' => [
                    'message' => 'Oops... we encountered an issue.',
                    'errors' => [
                        'player_id' => [
                            'The player id field is required.'
                        ],
                        'game_id' => [
                            'The game id can\'t found.'
                        ],
                        'pawn_id' => [
                            'The pawn id can\'t found.'
                        ],
                    ]
                ]
            ],
            [
                'payload' => [
                    'player_id' => 'player',
                    'game_id' => Str::uuid(),
                    'pawn_id' => Str::uuid(),
                    'number_of_moves' => 6,
                    'is_sent_back' => false
                ],
                'validationErrors' => [
                    'message' => 'Oops... we encountered an issue.',
                    'errors' => [
                        'player_id' => [
                            'The player id field must be a valid UUID.',
                            'The player id can\'t found.',
                            'Player has not started game yet.'
                        ],
                        'pawn_id' => [
                            'The pawn id can\'t found.'
                        ],
                        'game_id' => [
                            'The game id can\'t found.'
                        ],
                    ]
                ]
            ],
            [
                'payload' => [
                    'player_id' => Str::uuid(),
                    'game_id' => Str::uuid(),
                    'pawn_id' => Str::uuid(),
                    'number_of_moves' => 6,
                    'is_sent_back' => false
                ],
                'validationErrors' => [
                    'message' => 'Oops... we encountered an issue.',
                    'errors' => [
                        'player_id' => [
                            'The player id can\'t found.',
                            'Player has not started game yet.'
                        ],
                        'pawn_id' => [
                            'The pawn id can\'t found.'
                        ],
                        'game_id' => [
                            'The game id can\'t found.'
                        ],
                    ]
                ]
            ],
            [
                'payload' => [
                    'player_id' => Str::uuid(),
                    'game_id' => '',
                    'pawn_id' => Str::uuid(),
                    'number_of_moves' => 6,
                    'is_sent_back' => false
                ],
                'validationErrors' => [
                    'message' => 'Oops... we encountered an issue.',
                    'errors' => [
                        'player_id' => [
                            'The player id can\'t found.',
                            'Player has not started game yet.'
                        ],
                        'game_id' => [
                            'The game id field is required.'
                        ],
                        'pawn_id' => [
                            'The pawn id can\'t found.'
                        ]
                    ]
                ]
            ],
            [
                'payload' => [
                    'player_id' => Str::uuid(),
                    'game_id' => Str::uuid(),
                    'pawn_id' => Str::uuid(),
                    'number_of_moves' => 6,
                    'is_sent_back' => false
                ],
                'validationErrors' => [
                    'message' => 'Oops... we encountered an issue.',
                    'errors' => [
                        'player_id' => [
                            'The player id can\'t found.',
                            'Player has not started game yet.'
                        ],
                        'pawn_id' => [
                            'The pawn id can\'t found.'
                        ],
                        'game_id' => [
                            'The game id can\'t found.'
                        ],
                    ]
                ]
            ],
            [
                'payload' => [
                    'player_id' => Str::uuid(),
                    'game_id' => 'game-id',
                    'pawn_id' => Str::uuid(),
                    'number_of_moves' => 6,
                    'is_sent_back' => false
                ],
                'validationErrors' => [
                    'message' => 'Oops... we encountered an issue.',
                    'errors' => [
                        'player_id' => [
                            'The player id can\'t found.',
                            'Player has not started game yet.'
                        ],
                        'game_id' => [
                            'The game id field must be a valid UUID.',
                            'The game id can\'t found.'
                        ],
                        'pawn_id' => [
                            'The pawn id can\'t found.'
                        ],
                    ]
                ]
            ],
            [
                'payload' => [
                    'player_id' => Str::uuid(),
                    'game_id' => Str::uuid(),
                    'pawn_id' => '',
                    'number_of_moves' => 6,
                    'is_sent_back' => false
                ],
                'validationErrors' => [
                    'message' => 'Oops... we encountered an issue.',
                    'errors' => [
                        'player_id' => [
                            'The player id can\'t found.',
                            'Player has not started game yet.'
                        ],
                        'pawn_id' => [
                            'The pawn id field is required.'
                        ],
                        'game_id' => [
                            'The game id can\'t found.'
                        ],
                    ]
                ]
            ],
            [
                'payload' => [
                    'player_id' => Str::uuid(),
                    'game_id' => Str::uuid(),
                    'pawn_id' => 'pawn-index',
                    'number_of_moves' => 6,
                    'is_sent_back' => false
                ],
                'validationErrors' => [
                    'message' => 'Oops... we encountered an issue.',
                    'errors' => [
                        'player_id' => [
                            'The player id can\'t found.',
                            'Player has not started game yet.'
                        ],
                        'pawn_id' => [
                            'The pawn id field must be a valid UUID.',
                            'The pawn id can\'t found.'
                        ],
                        'game_id' => [
                            'The game id can\'t found.'
                        ],
                    ]
                ]
            ],
            [
                'payload' => [
                    'player_id' => Str::uuid(),
                    'game_id' => Str::uuid(),
                    'pawn_id' => Str::uuid(),
                    'number_of_moves' => 6,
                    'is_sent_back' => false
                ],
                'validationErrors' => [
                    'message' => 'Oops... we encountered an issue.',
                    'errors' => [
                        'player_id' => [
                            'The player id can\'t found.',
                            'Player has not started game yet.'
                        ],
                        'pawn_id' => [
                            'The pawn id can\'t found.'
                        ],
                        'game_id' => [
                            'The game id can\'t found.'
                        ],
                    ]
                ]
            ],
            [
                'payload' => [
                    'player_id' => Str::uuid(),
                    'game_id' => Str::uuid(),
                    'pawn_id' => Str::uuid(),
                    'number_of_moves' => '',
                    'is_sent_back' => false
                ],
                'validationErrors' => [
                    'message' => 'Oops... we encountered an issue.',
                    'errors' => [
                        'player_id' => [
                            'The player id can\'t found.',
                            'Player has not started game yet.'
                        ],
                        'number_of_moves' => [
                            'The number of moves field is required.'
                        ],
                        'game_id' => [
                            'The game id can\'t found.'
                        ],
                        'pawn_id' => [
                            'The pawn id can\'t found.'
                        ],
                    ]
                ]
            ],
            [
                'payload' => [
                    'player_id' => Str::uuid(),
                    'game_id' => Str::uuid(),
                    'pawn_id' => Str::uuid(),
                    'number_of_moves' => 'number of moves',
                    'is_sent_back' => false
                ],
                'validationErrors' => [
                    'message' => 'Oops... we encountered an issue.',
                    'errors' => [
                        'player_id' => [
                            'The player id can\'t found.',
                            'Player has not started game yet.'
                        ],
                        'number_of_moves' => [
                            'The number of moves field must be a number.'
                        ],
                        'game_id' => [
                            'The game id can\'t found.'
                        ],
                        'pawn_id' => [
                            'The pawn id can\'t found.'
                        ],
                    ]
                ]
            ],
            [
                'payload' => [
                    'player_id' => Str::uuid(),
                    'game_id' => Str::uuid(),
                    'pawn_id' => Str::uuid(),
                    'number_of_moves' => 6,
                    'is_sent_back' => ''
                ],
                'validationErrors' => [
                    'message' => 'Oops... we encountered an issue.',
                    'errors' => [
                        'player_id' => [
                            'The player id can\'t found.',
                            'Player has not started game yet.'
                        ],
                        'game_id' => [
                            'The game id can\'t found.'
                        ],
                        'pawn_id' => [
                            'The pawn id can\'t found.'
                        ],
                        'is_sent_back' => [
                            'The is sent back field is required.'
                        ]
                    ]
                ]
            ],
            [
                'payload' => [
                    'player_id' => Str::uuid(),
                    'game_id' => Str::uuid(),
                    'pawn_id' => Str::uuid(),
                    'number_of_moves' => 6,
                    'is_sent_back' => 'moves'
                ],
                'validationErrors' => [
                    'message' => 'Oops... we encountered an issue.',
                    'errors' => [
                        'player_id' => [
                            'The player id can\'t found.',
                            'Player has not started game yet.'
                        ],
                        'game_id' => [
                            'The game id can\'t found.'
                        ],
                        'pawn_id' => [
                            'The pawn id can\'t found.'
                        ],
                        'is_sent_back' => [
                            'The is sent back field must be true or false.'
                        ]
                    ]
                ]
            ]
        ];
    }

    private function movePawns(array $payload): TestResponse
    {
        return $this->postJson('api/move-pawns', $payload);
    }

    private function assertNotAllowedToMovePawnsTillInvalidPayload(TestResponse $response, array $validationErrors): void
    {
        $response->assertUnprocessable();

        $this->assertEquals($validationErrors, $response->json());
    }

    protected function assertNotAbleToMovePawnsUntilPlayerStartAGame(TestResponse $response): void
    {
        $response->assertUnprocessable();

        $this->assertEquals(
            [
                'message' => 'Oops... we encountered an issue.',
                'errors' => [
                    'player_id' => [
                        'Player has not started game yet.'
                    ],
                    'game_id' => [
                        'The game id can\'t found.'
                    ]
                ]
            ],
            $response->json()
        );
    }

    protected function assertPawnsMoved(TestResponse $response): void
    {
        $response->assertOk();

        $this->assertPawnMoved();
    }

    protected function assertPawnMoved(): void
    {
        $this->assertDatabaseHas(
            'moves',
            [
                'player_id' => $this->player->getKey(),
                'game_id' => $this->game->getKey(),
                'pawn_id' => $this->pawn->getKey(),
                'number_of_moves' => 6,
                'is_sent_back' => false
            ]
        );
    }
}
