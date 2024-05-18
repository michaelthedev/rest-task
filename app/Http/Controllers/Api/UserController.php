<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

final class UserController extends Controller
{

    public function get(): JsonResponse
    {
        return response()->json([
            'message' => 'success',
            'data' => auth()->user()
        ]);
    }
}
