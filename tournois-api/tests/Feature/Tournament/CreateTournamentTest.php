<?php

namespace Tests\Feature\Tournament;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class CreateTournamentTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_tournament(): void
    {
        // Set fixed time
        Carbon::setTestNow('2025-03-24 10:03:25');

        // Create and authenticate user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $loginResponse = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $token = $loginResponse->json('authorization.token');

        // Create tournament
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/tournaments', [
            'name' => 'Test Tournament',
            'description' => 'Test Description',
            'start_date' => '2025-04-01',
            'end_date' => '2025-04-15'
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Tournament created successfully',
                'data' => [
                    'tournament' => [
                        'name' => 'Test Tournament',
                        'description' => 'Test Description',
                        'start_date' => '2025-04-01',
                        'end_date' => '2025-04-15',
                        'user_id' => $user->id
                    ]
                ]
            ]);

        $this->assertDatabaseHas('tournaments', [
            'name' => 'Test Tournament',
            'description' => 'Test Description',
            'user_id' => $user->id
        ]);
    }

    public function test_unauthenticated_user_cannot_create_tournament(): void
    {
        $response = $this->postJson('/api/v1/tournaments', [
            'name' => 'Test Tournament',
            'description' => 'Test Description',
            'start_date' => '2025-04-01',
            'end_date' => '2025-04-15'
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    public function test_cannot_create_tournament_with_invalid_dates(): void
    {
        // Create and authenticate user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $loginResponse = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $token = $loginResponse->json('authorization.token');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/tournaments', [
            'name' => 'Test Tournament',
            'description' => 'Test Description',
            'start_date' => '2024-03-24', // Past date
            'end_date' => '2024-03-23'    // Before start date
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['start_date', 'end_date']);
    }
}