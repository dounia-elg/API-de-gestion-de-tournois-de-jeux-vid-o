<?php

namespace Tests\Unit\Tournament;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tournament;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetPlayersTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_tournament_players(): void
    {
        // Create a user
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        // Create a tournament
        $tournament = Tournament::create([
            'name' => 'Test Tournament',
            'description' => 'Test Description',
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-02',
            'user_id' => $user->id
        ]);

        // Register player
        $tournament->players()->attach($user->id);

        // Get players list
        $response = $this->getJson("/api/v1/tournaments/{$tournament->id}/players");

        // Check response
        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Players retrieved successfully'
                ]);
    }
}