<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\CreateTask;
use App\Jobs\DeleteTask;
use App\Jobs\UpdateTask;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class TaskController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'string',
            'label' => 'string',
            'due_date' => 'date',
        ]);

        $task = auth()->user()->tasks()->create([
            'uid' => uniqid(),
            'title' => $request->title,
            'description' => $request->description,
            'label' => $request->label,
            'due_date' => $request->due_date,
        ]);

        $this->broadcast('create', $task);

        return response()->json([
            'message' => 'Task created',
            'data' => $task,
        ], 201);
    }

    public function get(): JsonResponse
    {
        // paginate tasks
        $tasks = auth()->user()
            ->tasks()
            ->paginate(10);

        return response()->json([
            'message' => 'success',
            'data' => $tasks,
        ]);
    }

    public function find(string $uid): JsonResponse
    {
        $task = $this->getTask($uid);
        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $task,
        ]);
    }

    /**
     * Update a task by its unique id
     * It'll only update data passed to it
     * @param Request $request
     * @param string $uid
     * @return JsonResponse
     */
    public function update(Request $request, string $uid): JsonResponse
    {
        $task = $this->getTask($uid);
        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        $task->update(
            $request->only($task->getFillable())
        );

        $this->broadcast('update', $task);

        return response()->json([
            'message' => 'Task updated',
            'data' => $task,
        ]);
    }

    public function delete(string $uid): JsonResponse
    {
        $task = $this->getTask($uid);
        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        $task->delete();

        $this->broadcast('delete', [
            'uid' => $uid,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Task deleted',
        ]);
    }

    private function getTask(string $uid): ?Task
    {
        return auth()->user()
            ->tasks()
            ->where('uid', $uid)
            ->first();
    }

    private function broadcast(string $event, Task|array $data): void
    {
        if (config('broadcasting.default') === null) {
            return;
        }

        match ($event) {
            'create' => CreateTask::dispatch($data),
            'update' => UpdateTask::dispatch($data),
            'delete' => DeleteTask::dispatch($data),
        };
    }
}
