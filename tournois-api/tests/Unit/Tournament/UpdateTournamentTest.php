<?php

namespace Tests\Unit\Tournament;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tournament;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTournamentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_tournament(): void
    {
        // Create test user
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        // Create test tournament
        $tournament = Tournament::create([
            'name' => 'Old Name',
            'description' => 'Old Description',
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-02',
            'user_id' => $user->id
        ]);

        // Send update request
        $response = $this->putJson("/api/v1/tournaments/{$tournament->id}", [
            'name' => 'New Name',
            'description' => 'New Description',
            'start_date' => '2025-02-01',
            'end_date' => '2025-02-02'
        ]);

        // Check response
        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Tournament updated successfully'
                ]);
    }
}