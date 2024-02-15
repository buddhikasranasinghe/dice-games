<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Str;
use Domain\Ludo\Enums\GameStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    protected $model = Game::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid()->toString(),
            'status' => GameStatus::PLAYING,
            'challenger_id' => User::factory()->create()->getKey(),
            'type' => 'LUDO',
            'settings' => [
                'player_mode' => 'auto',
                'number_of_players' => 2,
                'winners' => [

                ]
            ]
        ];
    }

    public function forPlayer(User $player): self
    {
        return $this->state(['challenger_id' => $player->getKey()]);
    }
}
