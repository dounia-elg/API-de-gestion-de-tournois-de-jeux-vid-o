<?php

namespace Tests\Unit\GameMatch;

use Tests\TestCase;
use App\Models\User;
use App\Models\GameMatch;
use App\Models\Tournament;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteMatchTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_delete_match(): void
    {
        // Create and login as user
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        // Create tournament first
        $tournament = Tournament::create([
            'name' => 'Test Tournament',
            'description' => 'Test Description',
            'start_date' => '2025-03-26',
            'end_date' => '2025-04-26',
            'user_id' => $user->id
        ]);

        // Create match to delete
        $match = GameMatch::create([
            'tournament_id' => $tournament->id,
            'match_date' => '2025-03-27',
            'status' => 'pending'
        ]);

        // Send delete request
        $response = $this->deleteJson("/api/v1/matches/{$match->id}");

        // Check response
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Match deleted successfully'
            ]);
    }
}