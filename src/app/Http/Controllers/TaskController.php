<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use App\Services\ValidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function index(Request $request, Task $task): JsonResponse
    {
        return response()->json($task->with('status:id,name')->get());
    }

    public function show(Request $request, ValidationService $validationService, TaskService $taskService, Task $task): JsonResponse
    {
        $taskData = $validationService->validateModelEntity($task, $request->id);
        $taskData->status = $taskService->getStatusName($taskData->status_id);
        return response()->json($taskData);
    }

    public function create(Request $request, ValidationService $validationService, TaskService $taskService, Task $task): JsonResponse
    {
        $parameters = $validationService->validateParameters($request, ['title' => 'required|string|max:255', 'description' => 'string', 'status' => 'string']);
        return response()->json($taskService->saveTask($task, $parameters));
    }

    public function edit(Request $request, ValidationService $validationService, TaskService $taskService, Task $task): JsonResponse
    {
        $parameters = $validationService->validateParameters($request, ['title' => 'required|string|max:255', 'status' => 'required|string',]);
        $taskData = $validationService->validateModelEntity($task, $request->id);
        return response()->json($taskService->saveTask($taskData, $parameters));
    }

    public function update(Request $request, ValidationService $validationService, TaskService $taskService, Task $task): JsonResponse
    {
        $parameters = $validationService->validateParameters($request, ['status' => 'required|string']);
        $taskData = $validationService->validateModelEntity($task, $request->id);
        return response()->json(['success' => (bool)$taskService->updateStatus($taskData, $parameters['status'])]);
    }

    public function destroy(Request $request, ValidationService $validationService, Task $task): JsonResponse
    {
        $taskData = $validationService->validateModelEntity($task, $request->id);
        return response()->json(['success' => (bool)$taskData->delete()]);
    }
}
