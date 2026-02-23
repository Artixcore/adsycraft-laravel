<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(mixed $data = null, string $message = 'OK', ?array $meta = null, int $status = 200): JsonResponse
    {
        $body = ['ok' => true, 'message' => $message];
        if ($data !== null) {
            $body['data'] = $data;
        }
        if ($meta !== null) {
            $body['meta'] = $meta;
        }

        return response()->json($body, $status);
    }

    public static function error(string $message, ?array $errors = null, int $status = 400): JsonResponse
    {
        $body = ['ok' => false, 'message' => $message];
        if ($errors !== null) {
            $body['errors'] = $errors;
        }

        return response()->json($body, $status);
    }
}
