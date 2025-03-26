<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\GameMatchController;

Route::group(['middleware' => 'api'], function () {
    Route::prefix('v1')->group(function () {
        // Auth routes
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        
        // Protected routes
        Route::middleware('auth:api')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('user', [AuthController::class, 'user']);
            
            // Tournament routes
            Route::post('tournaments', [TournamentController::class, 'store']);

            Route::get('tournaments', [TournamentController::class, 'index']);

            Route::get('tournaments/{id}', [TournamentController::class, 'show']);

            Route::put('tournaments/{id}', [TournamentController::class, 'update']);

            Route::delete('tournaments/{id}', [TournamentController::class, 'destroy']);



            Route::post('tournaments/{tournament_id}/players', [PlayerController::class, 'register']);

            Route::get('tournaments/{tournament_id}/players', [PlayerController::class, 'getPlayers']);

            Route::delete('tournaments/{tournament_id}/players/{player_id}', [PlayerController::class, 'unregister']);



            Route::post('matches', [GameMatchController::class, 'store']);
        });
    });
});