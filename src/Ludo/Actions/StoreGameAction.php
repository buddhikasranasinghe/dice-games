<?php

namespace Domain\Ludo\Actions;

use Exception;
use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Str;
use Domain\Ludo\Enums\GameStatus;
use Domain\Ludo\Commands\StoreGameCommand;
use Domain\Ludo\Exception\AlreadyExistPlayingGameException;

class StoreGameAction
{
    protected User $challenger;
    protected StoreGameCommand $command;

    /**
     * @throws Exception
     */
    public function execute(User $creator, StoreGameCommand $command): Game
    {
        $this->challenger = $creator;
        $this->command = $command;

        if ($this->hasPlayingGame()) {
            throw new AlreadyExistPlayingGameException();
        }

        return $this->storeGame();
    }

    protected function hasPlayingGame(): bool
    {
        return $this->challenger->games->where('status', GameStatus::PLAYING->value)->count() > 0;
    }

    protected function storeGame(): Game
    {
        $game = new Game;

        $game->forceFill([
            'id' => Str::uuid(),
            'challenger_id' => $this->challenger->getKey(),
            'settings' => $this->command->settings,
            'status' => $this->command->status,
            'type' => 'Ludo'
        ]);

        $game->save();

        return $game;
    }
}
