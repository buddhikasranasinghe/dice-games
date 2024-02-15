<?php

namespace App\Rules;

use Closure;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Validation\ValidationRule;

class HavePlayers implements ValidationRule
{
    protected Collection $invalidPlayers;

    public function __construct()
    {
        $this->invalidPlayers = new Collection;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_array($value)) return;

        if ($this->isInvalidPlayersExist($value)) {
            $fail($this->getErrorMessage());
        }
    }

    protected function isInvalidPlayersExist(array $playerIds): bool
    {
        $this->setInvalidPlayers($playerIds);

        if ($this->invalidPlayers->isNotEmpty()) {
            return true;
        }

        return false;
    }

    protected function setInvalidPlayers(array $playerIds): void
    {
        foreach ($playerIds as $playerId) {
            $player = User::query()->whereKey($playerId)->first();

            if (!$player) {
                $this->invalidPlayers->push($playerId);
            }
        }
    }

    protected function getErrorMessage(): string
    {
        $playerIdText = '';

        if ($this->invalidPlayers->count() === 1) {
            $playerIdText = $this->invalidPlayers->first().' player is ';
        } else {
            foreach ($this->invalidPlayers as $key => $player) {
                if (($key + 1) < ($this->invalidPlayers->count() - 1)) {
                    $playerIdText .= $player.', ';
                } elseif (($key + 1) === ($this->invalidPlayers->count() - 1)) {
                    $playerIdText .= $player.' and ';
                } else {
                    $playerIdText .= $player.' players are ';
                }
            }
        }

        return $playerIdText."not found.";
    }
}
