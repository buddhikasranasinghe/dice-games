<?php

namespace App\Rules;

use Closure;
use App\Models\Game;
use Illuminate\Contracts\Validation\ValidationRule;

class HasStartedGame implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->isHasStartedGame()) {
            $fail('Player has not started game yet.');
        }
    }

    protected function isHasStartedGame(): bool
    {
        return Game::where('challenger_id', request()->get('player_id'))
                ->where('status', 'PLAYING')->count() > 0;
    }
}
