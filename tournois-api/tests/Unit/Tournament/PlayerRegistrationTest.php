<?php

namespace Tests\Unit\Tournament;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tournament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\PlayerController;

class PlayerRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_player_can_register_for_tournament(): void
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

        // Try to register for tournament
        $response = $this->postJson("/api/v1/tournaments/{$tournament->id}/players");

        // Check response
        $response->assertStatus(201)
                ->assertJson([
                    'message' => 'Registration successful'
                ]);
    }
}