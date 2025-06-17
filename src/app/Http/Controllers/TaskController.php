<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function index(Request $request, Task $task): JsonResponse
    {
        return response()->json($task->all());
    }

    public function show(Request $request, Task $task): JsonResponse
    {
        $taskData = $task->find($request->id);
        return $taskData ? response()->json($taskData) : $this->errorResponse('Task Not Found', Response::HTTP_NOT_FOUND);
    }

    public function create(Request $request, Task $task): JsonResponse
    {
        $taskParams = $request->validate([]);
        $taskData = $task->create($taskParams);
        return response()->json([]);
    }

    public function edit(Request $request, Task $task): JsonResponse
    {
        $taskData = $task->find($request->id);
        if (!$taskData) {
            return  $this->errorResponse('Task Not Found', Response::HTTP_NOT_FOUND);
        }

        return response()->json([]);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        $taskData = $task->find($request->id);
        if (!$taskData) {
            return  $this->errorResponse('Task Not Found', Response::HTTP_NOT_FOUND);
        }

        return response()->json($task->update());
    }

    public function destroy(Request $request, Task $task): JsonResponse
    {
        $taskData = $task->find($request->id);
        return $taskData ? response()->json(['success' => (bool)$taskData->delete()]) : $this->errorResponse('Task Not Found', Response::HTTP_NOT_FOUND);
    }
}
