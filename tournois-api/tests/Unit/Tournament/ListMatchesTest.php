<?php

namespace Tests\Unit\GameMatch;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tournament;
use App\Models\GameMatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\GameMatchController;

class ListMatchesTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_matches(): void
    {
        // Create user and login
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        // Create a tournament
        $tournament = Tournament::create([
            'name' => 'Test Tournament',
            'description' => 'Test Description',
            'start_date' => '2025-03-26',
            'end_date' => '2025-04-26',
            'user_id' => $user->id
        ]);

        // Create a match
        $match = GameMatch::create([
            'tournament_id' => $tournament->id,
            'match_date' => '2025-03-27',
            'status' => 'pending'
        ]);

        // Get matches list
        $response = $this->getJson('/api/v1/matches');

        // Check response
        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Matches retrieved successfully'
                ]);
    }
}