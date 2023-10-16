<?php

namespace App\Services;

class ErrorResponder
{
    public function make(string $message, int $statusCode, string $details = ''): \Illuminate\Http\JsonResponse
    {
        $error = ['error' => $message];
        if (!empty($details)) {
            $error['details'] = $details;
        }
        return response()->json($error, $statusCode);
    }
}
