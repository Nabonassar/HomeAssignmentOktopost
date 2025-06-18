<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function get(Request $request, TaskService $taskService, Task $task): JsonResponse
    {
        return response()->json($taskService->getStats($request->all(), $task));
    }
}
