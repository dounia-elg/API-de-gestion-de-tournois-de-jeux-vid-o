<?php

namespace Tests\Unit\Tournament;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tournament;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTournamentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_delete_tournament(): void
    {
        // Create a user
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        // Create a tournament to delete
        $tournament = Tournament::create([
            'name' => 'Test Tournament',
            'description' => 'Test Description',
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-02',
            'user_id' => $user->id
        ]);

        // Try to delete the tournament
        $response = $this->deleteJson("/api/v1/tournaments/{$tournament->id}");

        // Check if deletion was successful
        $response->assertStatus(200);
        $this->assertDatabaseMissing('tournaments', ['id' => $tournament->id]);
    }
}