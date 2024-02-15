<?php

namespace App\Http\Controllers\Ludo;

use App\Http\Controllers\Controller;
use App\Http\Requests\MovePawnsRequest;
use Domain\Ludo\Actions\MovePawnsAction;
use Illuminate\Http\Request;

class MovePawnsController extends Controller
{
    public function __invoke(MovePawnsAction $action, MovePawnsRequest $pawnsRequest)
    {
        $action->execute($pawnsRequest->user(), $pawnsRequest->command());
    }
}
