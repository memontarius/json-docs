<?php

namespace App\Services;

class ErrorResponder
{
    /**
     * Create a response with specified parameters
     *
     * @param string $message
     * @param int $statusCode
     * @param string $details
     * @return \Illuminate\Http\JsonResponse
     */
    public function make(string $message, int $statusCode, string $details = ''): \Illuminate\Http\JsonResponse
    {
        $error = ['error' => $message];
        if (!empty($details)) {
            $error['details'] = $details;
        }
        return response()->json($error, $statusCode);
    }
}
