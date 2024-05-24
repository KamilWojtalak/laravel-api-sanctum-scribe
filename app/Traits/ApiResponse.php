<?php

namespace App\Traits;

trait ApiResponse
{
    public function success(string $message, array $data = [], int $statusCode = 200)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode,
        ], $statusCode);
    }

    public function error($errors = [], int $statusCode = 404)
    {
        if (is_string($errors)) {
            return response()->json([
                'message' => $errors,
                'status' => $statusCode,
            ], $statusCode);
        }

        return response()->json([
            'errors' => $errors,
        ], $statusCode);
    }

    public function ok(string $message, array $data = [])
    {
        return $this->success($message, $data, 200);
    }
}
