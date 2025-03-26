<?php

namespace Tests\Unit\Tournaments;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tournament;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GameMatchTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_match(): void
    {
        // Create and login as user
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        // Create tournament
        $tournament = Tournament::create([
            'name' => 'Test Tournament',
            'description' => 'Test Description',
            'start_date' => '2025-03-26',
            'end_date' => '2025-04-26',
            'user_id' => $user->id
        ]);

        // Create two players
        $player1 = User::factory()->create();
        $player2 = User::factory()->create();

        // Register players in tournament
        $tournament->players()->attach([$player1->id, $player2->id]);

        // Try to create a match
        $response = $this->postJson('/api/v1/matches', [
            'tournament_id' => $tournament->id,
            'match_date' => '2025-03-27 10:00:00',
            'player_ids' => [$player1->id, $player2->id]
        ]);

        // Check if match was created
        $response->assertStatus(201);
    }
}