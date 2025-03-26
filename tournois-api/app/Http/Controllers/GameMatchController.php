<?php

namespace App\Http\Controllers;

use App\Models\GameMatch;
use App\Models\Tournament;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GameMatchController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'match_date' => 'required|date|after:now',
            'player_ids' => 'required|array|min:2',
            'player_ids.*' => 'exists:users,id'
        ]);

        $tournament = Tournament::findOrFail($request->tournament_id);
        $tournamentPlayers = $tournament->players()->pluck('users.id')->toArray();
        
        if ($invalidPlayers = array_diff($request->player_ids, $tournamentPlayers)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Some players are not registered in this tournament',
                'invalid_players' => $invalidPlayers
            ], 422);
        }

        $match = GameMatch::create([
            'tournament_id' => $request->tournament_id,
            'match_date' => $request->match_date,
            'status' => 'pending'
        ]);

        $match->players()->attach($request->player_ids);
        $match->load(['tournament', 'players']);

        return response()->json([
            'status' => 'success',
            'message' => 'Match created successfully',
            'data' => [
                'match' => $match,
                'timestamp' => '2025-03-26 07:30:33',
                'user' => 'dounia-elg'
            ]
        ], 201);
    }
}