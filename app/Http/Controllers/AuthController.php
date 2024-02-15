<?php

namespace App\Http\Controllers;

use Domain\User\SignUpAction;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\SignUpRequest;

class AuthController extends Controller
{
    public function register(SignUpRequest $request, SignUpAction $action): JsonResponse
    {
        $user = $action->execute($request->command());

        return response()->json(['user' => $user], Response::HTTP_OK);
    }
}
