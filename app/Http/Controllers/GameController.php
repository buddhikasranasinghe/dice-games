<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CreateGameRequest;
use Domain\Ludo\Actions\StoreGameAction;
use Domain\Ludo\Exception\AlreadyExistPlayingGameException;

class GameController extends Controller
{
    public function store(CreateGameRequest $request, StoreGameAction $action): JsonResponse
    {
        try {
            $game = $action->execute(request()->user(), $request->command());

            return response()->json(['game' => $game], Response::HTTP_CREATED);
        } catch (AlreadyExistPlayingGameException $e) {
            return response()->json([], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
