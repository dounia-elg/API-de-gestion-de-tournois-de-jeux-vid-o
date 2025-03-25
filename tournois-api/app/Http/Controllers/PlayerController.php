<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function register($tournament_id): JsonResponse
    {
        // Find tournament
        $tournament = Tournament::find($tournament_id);
        
        if (!$tournament) {
            return response()->json([
                'message' => 'Tournament not found'
            ], 404);
        }

        // Check if already registered
        if ($tournament->players()->where('user_id', auth()->id())->exists()) {
            return response()->json([
                'message' => 'Already registered'
            ], 400);
        }

        // Register player
        $tournament->players()->attach(auth()->id());

        return response()->json([
            'message' => 'Registration successful',
            'data' => $tournament
        ], 201);
    }


    public function getPlayers($tournament_id): JsonResponse
    {
        $tournament = Tournament::find($tournament_id);
        
        if (!$tournament) {
            return response()->json([
                'message' => 'Tournament not found'
            ], 404);
        }

        $players = $tournament->players;

        return response()->json([
            'message' => 'Players retrieved successfully',
            'data' => $players
        ]);
    }

    public function unregister($tournament_id, $player_id): JsonResponse
{
    $tournament = Tournament::find($tournament_id);
    
    if (!$tournament) {
        return response()->json([
            'message' => 'Tournament not found'
        ], 404);
    }

    // Check if player is registered
    if (!$tournament->players()->where('user_id', $player_id)->exists()) {
        return response()->json([
            'message' => 'Player not registered in this tournament'
        ], 404);
    }

    // Unregister player
    $tournament->players()->detach($player_id);

    return response()->json([
        'message' => 'Player unregistered successfully'
    ]);
}
}