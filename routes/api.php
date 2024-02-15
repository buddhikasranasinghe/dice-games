<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\Ludo\MovePawnsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('sign-up', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('game', [GameController::class, 'store']);

    Route::post('move-pawns', MovePawnsController::class);
});
