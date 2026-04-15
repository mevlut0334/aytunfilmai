<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    protected function success(mixed $data = null, string $message = "Islem basarili", int $code = 200): JsonResponse
    {
        return response()->json([
            "status"  => true,
            "message" => $message,
            "data"    => $data,
        ], $code);
    }

    protected function error(string $message = "Bir hata olustu", int $code = 400, mixed $data = null): JsonResponse
    {
        return response()->json([
            "status"  => false,
            "message" => $message,
            "data"    => $data,
        ], $code);
    }
}
