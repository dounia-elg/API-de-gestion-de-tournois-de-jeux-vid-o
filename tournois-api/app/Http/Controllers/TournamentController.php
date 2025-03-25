<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tournament;
use Illuminate\Http\JsonResponse;

class TournamentController extends Controller
{   


    public function index(): JsonResponse
    {
        $tournaments = Tournament::all();
        
        return response()->json([
            'status' => 'success',
            'data' => $tournaments
        ]);
    }
    
    public function store(Request $request)
    {
        $tournament = Tournament::create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tournament created successfully',
            'data' => $tournament
        ], 201);
    }

    
}