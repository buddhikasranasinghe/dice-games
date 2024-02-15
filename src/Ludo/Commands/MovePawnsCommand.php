<?php

namespace Domain\Ludo\Commands;

use App\Models\Game;
use App\Models\User;

class MovePawnsCommand
{
    public string $gameKey;
    public string $pawnKey;
    public int $numberOfMoves;
    public bool $isSentBack;
}
