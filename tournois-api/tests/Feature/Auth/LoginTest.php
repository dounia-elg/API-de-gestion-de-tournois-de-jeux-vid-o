<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful user login
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                    'authorization' => [
                        'token',
                        'type'
                    ]
                ]
            ])
            ->assertJson([
                'status' => 'success',
                'message' => 'Logged in successfully',
                'data' => [
                    'user' => [
                        'email' => 'test@example.com'
                    ],
                    'authorization' => [
                        'type' => 'bearer'
                    ]
                ]
            ]);

        $this->assertNotEmpty($response['data']['authorization']['token']);
    }

    /**
     * Test login with invalid credentials
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'wrong_password',
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The provided credentials are incorrect.',
                'errors' => [
                    'email' => [
                        'The provided credentials are incorrect.'
                    ]
                ]
            ]);
    }

    /**
     * Test login validation for required email
     */
    public function test_login_requires_email(): void
    {
        $response = $this->postJson('/api/v1/login', [
            'password' => 'password123',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email'])
            ->assertJson([
                'message' => 'The email field is required.',
                'errors' => [
                    'email' => [
                        'The email field is required.'
                    ]
                ]
            ]);
    }

    /**
     * Test login validation for required password
     */
    public function test_login_requires_password(): void
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password'])
            ->assertJson([
                'message' => 'The password field is required.',
                'errors' => [
                    'password' => [
                        'The password field is required.'
                    ]
                ]
            ]);
    }

    /**
     * Test login validation for valid email format
     */
    public function test_login_requires_valid_email_format(): void
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'invalid-email',
            'password' => 'password123',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email'])
            ->assertJson([
                'message' => 'The email field must be a valid email address.',
                'errors' => [
                    'email' => [
                        'The email field must be a valid email address.'
                    ]
                ]
            ]);
    }
}