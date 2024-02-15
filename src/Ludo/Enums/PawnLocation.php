<?php

namespace Domain\Ludo\Enums;

enum PawnLocation: string
{
    case AT_HOME = 'at_home';
    case ON_TRACK = 'on_track';
    case AT_END = 'at_end';
}
