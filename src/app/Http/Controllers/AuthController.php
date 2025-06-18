<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ValidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function auth(Request $request, ValidationService $validationService): JsonResponse
    {
        $data = $validationService->validateParameters($request, ['email' => 'required|string|email', 'password' => 'required']);

        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return $this->errorResponse('Invalid Credentials', Response::HTTP_UNAUTHORIZED);
        }

        return response()->json(['access_token' => $user->createToken($user->name . '-AuthToken')->plainTextToken]);
    }
}
