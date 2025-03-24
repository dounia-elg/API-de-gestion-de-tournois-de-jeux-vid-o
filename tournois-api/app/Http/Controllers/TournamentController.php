<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tournament\CreateTournamentRequest;
use App\Models\Tournament;
use Illuminate\Http\JsonResponse;

class TournamentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(CreateTournamentRequest $request): JsonResponse
    {
        $tournament = Tournament::create([
            ...$request->validated(),
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tournament created successfully',
            'data' => [
                'tournament' => $tournament
            ]
        ], 201);
    }
}