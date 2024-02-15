<?php

namespace Domain\Ludo\Enums;

enum GameStatus: string
{
    case OPENNED = 'open';
    case PLAYING = 'playing';
    case ABOUNDED = 'abounded';
    case FINISHED = 'finished';
}
