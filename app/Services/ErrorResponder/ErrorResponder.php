<?php

namespace App\Services\ErrorResponder;

use Illuminate\Http\JsonResponse;

class ErrorResponder
{
    public function make(string $message, int $status, array $additional = []): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'errors' => [...$additional]
        ], $status);
    }

    public function makeByError(ResponseError $error, ?int $status = null, array $additional = []): JsonResponse
    {
        return match ($error) {
            ResponseError::PageNotFound => $this->make('Page not found', $status ?? 404, $additional),
            ResponseError::BadRequest => $this->make('Bad request', $status ?? 400, $additional),
            ResponseError::ValidationFailed => $this->make('Invalid input data', $status ?? 400, $additional),
            default => null,
        };
    }
}
