<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Domain\Ludo\Enums\GameStatus;
use Domain\Ludo\Commands\StoreGameCommand;
use Domain\Ludo\Enums\PlayerSelectionMode;

class CreateGameRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'settings.player_selection_mode' => [
                'required',
                Rule::enum(PlayerSelectionMode::class),
            ],
            'settings.total_players' => [
                'required',
                'numeric',
                Rule::in([2, 4])
            ],
//            'settings.players' => [
//                'required',
//                'array',
//                function (string $attribute, mixed $value, Closure $fail) {
//                    $expectedPlayerCount = in_array($this->input('settings.total_players'), [2, 4]) ?
//                        $this->input('settings.total_players') : 2;
//
//                    if (is_array($value) && count($value) !== $expectedPlayerCount) {
//                        $fail("The settings.players field must contain $expectedPlayerCount items.");
//                    }
//                },
//                new HavePlayers
//            ]
        ];
    }

    public function messages(): array
    {
        return [
            'settings.player_selection_mode.required' => 'The settings.player selection mode field is required.',
            'settings.player_selection_mode' => 'The settings.player selection mode field must be `auto` or `manual`.',
            'settings.total_players.in' => 'The settings.total players field should be 2 or 4.',
//            'settings.players.size' => 'The settings.players field must contain 2 items.'
        ];
    }

    public function command(): StoreGameCommand
    {
        $command = new StoreGameCommand;
        $command->settings = [
            'playerSelectionMode' => $this->input('settings.player_selection_mode'),
            'totalPlayers' => $this->input('settings.total_players'),
        ];
        $command->status = GameStatus::OPENNED;

        return $command;
    }
}
