<?php

namespace Tests\Feature\Api;

use App\Jobs\CreateTask;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected array $headers;

    public function setUp(): void
    {
        parent::setUp();

        Queue::fake();

        $this->user = User::factory()->create();

        $token = JWTAuth::fromUser($this->user);

        $this->headers = [
            'Authorization' => 'Bearer '.$token
        ];
    }

    public function test_user_can_create_task(): void
    {
        $data = [
            'title' => 'Test Task',
            'description' => 'Testing task creation',
            'label' => 'test.feature'
        ];

        $response = $this->withHeaders($this->headers)
            ->json('POST', '/api/tasks', $data);

        Queue::assertPushed(CreateTask::class);

        $response->assertStatus(201)
            ->assertJson([
                'data' => $data
            ]);
    }

    public function test_user_can_get_tasks(): void
    {
        $response = $this->withHeaders($this->headers)
            ->json('GET', '/api/tasks');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message', 'data'
            ]);
    }

    public function test_user_can_get_a_task(): void
    {
        $task = $this->user->tasks()->create([
            'uid' => uniqid(),
            'title' => 'Test Task',
            'description' => 'Testing task creation',
            'label' => 'test.feature'
        ]);

        $response = $this->withHeaders($this->headers)
            ->json('GET', '/api/tasks/'.$task->uid);

        $response->assertStatus(200)
            ->assertJson([
                'data' => $task->toArray()
            ]);
    }

    public function test_user_can_update_task(): void
    {
        $task = $this->user->tasks()->create([
            'uid' => uniqid(),
            'title' => 'Test Task to update',
            'description' => 'Testing task creation',
            'label' => 'test.feature'
        ]);

        $data = [
            'title' => 'Updated Task'
        ];

        $response = $this->withHeaders($this->headers)
            ->json('PATCH', '/api/tasks/'.$task->uid, $data);

        $response->assertStatus(200)
            ->assertJson([
                'data' => $data
            ]);
    }

    public function test_user_can_delete_task(): void
    {
        $task = $this->user->tasks()->create([
            'uid' => uniqid(),
            'title' => 'Test Task to delete',
            'description' => 'Testing task creation',
            'label' => 'test.feature'
        ]);

        $response = $this->withHeaders($this->headers)
            ->json('DELETE', '/api/tasks/'.$task->uid);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Task deleted'
            ]);
    }
}
