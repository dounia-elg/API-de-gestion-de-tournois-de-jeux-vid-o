<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tournament;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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


    public function show($id): JsonResponse
    {
        $tournament = Tournament::with('user')->find($id);

        if (!$tournament) {
            return response()->json([
                'message' => 'Tournament not found'
            ], 404);
        }

        return response()->json([
            'data' => $tournament,
        ]);
    }


    public function update(Request $request, $id): JsonResponse
    {
        $tournament = Tournament::find($id);
    
        if (!$tournament) {
            return response()->json([
                'message' => 'Tournament not found'
            ], 404);
        }
    
        $tournament->update([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);
    
        return response()->json([
            'message' => 'Tournament updated successfully',
            'data' => $tournament
        ]);
    }



    public function destroy($id): JsonResponse
    {
        $tournament = Tournament::find($id);

        if (!$tournament) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tournament not found'
            ], 404);
        }

        $tournament->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Tournament deleted successfully'
        ], 200);
    }
        
}