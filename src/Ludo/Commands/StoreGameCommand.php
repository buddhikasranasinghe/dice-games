<?php

namespace Domain\Ludo\Commands;

use Domain\Ludo\Enums\GameStatus;

class StoreGameCommand
{
    public array $settings;
    public GameStatus $status;
}
