<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Pawn;
use Domain\Ludo\Enums\PawnLocation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PawnFactory extends Factory
{
    protected $model = Pawn::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid()->toString(),
            'game_id' => Game::factory()->create()->getKey(),
            'status' => PawnLocation::ON_TRACK->value,
            'color' => 'green'
        ];
    }

    public function forGame(Game $game): self
    {
        return $this->state(['game_id' => $game->getKey()]);
    }
}
