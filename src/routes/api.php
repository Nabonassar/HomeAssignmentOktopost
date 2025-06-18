<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;


Route::post('/auth', [AuthController::class, 'auth']);
Route::get('/tasks/stats', [StatsController::class, 'get'])->middleware('auth:sanctum');
Route::get('/tasks', [TaskController::class, 'index'])->middleware('auth:sanctum');
Route::get('/tasks/{id}', [TaskController::class, 'show'])->middleware('auth:sanctum');
Route::post('/tasks', [TaskController::class, 'create'])->middleware('auth:sanctum');
Route::put('/tasks/{id}', [TaskController::class, 'edit'])->middleware('auth:sanctum');
Route::patch('/tasks/{id}', [TaskController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->middleware('auth:sanctum');
