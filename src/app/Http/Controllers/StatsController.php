<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function get(Request $request, Task $tasks): JsonResponse
    {
        return response()->json([]);
    }
}
