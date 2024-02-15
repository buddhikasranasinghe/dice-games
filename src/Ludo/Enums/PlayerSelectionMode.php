<?php

namespace Domain\Ludo\Enums;

enum PlayerSelectionMode: string
{
    case AUTOMATICALLY = 'auto';
    case MANUALLY = 'manual';
}
