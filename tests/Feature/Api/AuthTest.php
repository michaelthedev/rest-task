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
}
