<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_authenticated_user_info(): void
    {
        // Set fixed time for consistent testing
        Carbon::setTestNow('2025-03-24 06:30:54');

        // Create user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Login to get token
        $loginResponse = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $token = $loginResponse->json('authorization.token');

        // Get user info
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/user');

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => 'Test User',
                        'email' => 'test@example.com',
                        'created_at' => '2025-03-24T06:30:54.000000Z',
                        'updated_at' => '2025-03-24T06:30:54.000000Z'
                    ]
                ]
            ]);
    }

    public function test_cannot_get_user_info_without_token(): void
    {
        $response = $this->getJson('/api/v1/user');

        $response->assertStatus(401)
            ->assertExactJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    public function test_cannot_get_user_info_with_invalid_token(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid_token',
        ])->getJson('/api/v1/user');

        $response->assertStatus(401)
            ->assertExactJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    public function test_cannot_get_user_info_after_logout(): void
    {
        // Create user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Login
        $loginResponse = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $token = $loginResponse->json('authorization.token');

        // Logout
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/logout');

        // Try to get user info with the same token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/user');

        $response->assertStatus(401)
            ->assertExactJson([
                'message' => 'Unauthenticated.'
            ]);
    }
}