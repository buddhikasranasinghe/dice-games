<?php

namespace Domain\Ludo\Actions;

use App\Models\Moves;
use App\Models\User;
use Domain\Ludo\Commands\MovePawnsCommand;

class MovePawnsAction
{
    protected MovePawnsCommand $command;

    public function execute(User $player, MovePawnsCommand $command): void
    {
        $this->command = $command;

        $moves = new Moves;
        $moves->player_id = $player->getKey();
        $moves->game_id = $this->command->gameKey;
        $moves->pawn_id = $this->command->pawnKey;
        $moves->number_of_moves = $this->command->numberOfMoves;
        $moves->current_position = $this->getCurrentPosition();
        $moves->is_sent_back = $this->command->isSentBack;

        $moves->save();
    }

    protected function getCurrentPosition(): int
    {
        return $this->command->numberOfMoves;
    }
}
