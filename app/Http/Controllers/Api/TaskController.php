<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            'title' => $request->title,
            'description' => $request->description,
            'label' => $request->label,
            'due_date' => $request->due_date,
        ]);

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

    public function update(Request $request, string $uid): JsonResponse
    {
        $task = $this->getTask($uid);
        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

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
}
