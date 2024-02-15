<?php

namespace App\Http\Requests;

use Closure;
use App\Models\Game;
use App\Models\Pawn;
use App\Models\User;
use App\Rules\HasStartedGame;
use Domain\Ludo\Enums\PawnLocation;
use Domain\Ludo\Commands\MovePawnsCommand;

class MovePawnsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'player_id' => [
                'required',
                'uuid',
                function (string $attribute, mixed $value, Closure $fail) {
                    $player = User::query()
                        ->where('id', $value)
                        ->where('status', 'ACTIVE')
                        ->count();

                    if ($player === 0) {
                        $fail("The player id can't found.");
                    }
                },
                new HasStartedGame
            ],
            'game_id' => [
                'required',
                'uuid',
                function (string $attribute, mixed $value, Closure $fail) {
                    $game = Game::query()
                        ->where('id', $value)
                        ->whereNotIn('status', ['END', 'BOUNDEN'])
                        ->count();

                    if ($game === 0) {
                        $fail("The game id can't found.");
                    }
                }
             ],
            'pawn_id' => [
                'required',
                'uuid',
                function (string $attribute, mixed $value, Closure $fail) {
                    $game = Pawn::query()
                        ->where('id', $value)
                        ->whereIn('status', [PawnLocation::AT_HOME->value, PawnLocation::ON_TRACK->value])
                        ->count();

                    if ($game === 0) {
                        $fail("The pawn id can't found.");
                    }
                }
            ],
            'number_of_moves' => [
                'required',
                'numeric'
            ],
            'is_sent_back' => [
                'required',
                'boolean'
            ]
        ];
    }

    public function command(): MovePawnsCommand
    {
        $command = new MovePawnsCommand;

        $command->gameKey = $this->input('game_id');
        $command->pawnKey = $this->input('pawn_id');
        $command->numberOfMoves = $this->input('number_of_moves');
        $command->isSentBack = $this->input('is_sent_back');

        return $command;
    }
}
