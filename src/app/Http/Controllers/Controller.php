<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    /**
     * Given an error message and HTTP error code returns JSOn error message
     *
     * @param string $error
     * @param integer $errorCode
     * @return JsonResponse
     */
    public function errorResponse(string $error, int $errorCode): JsonResponse
    {
        return response()->json(['error' => $error], $errorCode);
    }
}
