<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function successResponse(
        mixed $data = null,
        string $message = 'Success',
        int $status = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    protected function errorResponse(
        string $message,
        int $status = 400
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => null,
        ], $status);
    }
}
