<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private array $userInfo = [
        'first_name' => 'Test',
        'last_name' => 'User',
        'password' => 'tester',
        'email' => 'feat@test.com',
    ];

    protected function create_user(): void
    {
        $data = $this->userInfo;
        $data['password'] = Hash::make($data['password']);

        User::create($data);
    }

    public function test_user_can_register(): void
    {
        $data = $this->userInfo;
        $data['password_confirmation'] = $data['password'];

        $response = $this->json('POST', '/api/auth/register', $data);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Registration successful'
            ]);

        $this->assertDatabaseHas('users', [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email']
        ]);
    }

    public function test_user_can_login(): void
    {
        $this->create_user();

        $data = $this->userInfo;

        $response = $this->json('POST', '/api/auth/login', $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'success'
            ]);
    }

    public function test_user_can_refresh_token(): void
    {
        $this->create_user();

        $user = User::where('email', $this->userInfo['email'])
            ->first();

        $token = JWTAuth::fromUser($user);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->json('POST', '/api/auth/refresh');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'access_token', 'token_type', 'expires_in'
                ]
            ]);
    }

    public function test_user_can_logout(): void
    {
        $this->create_user();

        $user = User::where('email', $this->userInfo['email'])
            ->first();

        $token = JWTAuth::fromUser($user);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->json('POST', '/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logout successful']);
    }

    public function test_user_cannot_register_with_existing_email(): void
    {
        // create first user
        $this->create_user();

        // attempt to create another with the same email
        $data = $this->userInfo;
        $data['password_confirmation'] = $data['password'];

        $response = $this->json('POST', '/api/auth/register', $data);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The email has already been taken.',
                'errors' => [
                    'email' => ['The email has already been taken.']
                ]
            ]);
    }
}
