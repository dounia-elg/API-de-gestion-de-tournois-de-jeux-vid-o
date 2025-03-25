<?php

namespace Tests\Unit\Tournament;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTournamentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_tournament(): void
    {
        // Create and login as a user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);
        $this->actingAs($user, 'api');

        // Send request to create tournament
        $response = $this->postJson('/api/v1/tournaments', [
            'name' => 'Test Tournament',
            'description' => 'Test Description',
            'start_date' => '2025-03-24',
            'end_date' => '2025-04-15'
        ]);

        // Assert response
        $response->assertStatus(201);
        $this->assertDatabaseHas('tournaments', [
            'name' => 'Test Tournament',
            'user_id' => $user->id
        ]);
    }
}