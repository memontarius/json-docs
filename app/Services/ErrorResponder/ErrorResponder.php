<?php

namespace App\Services\ErrorResponder;

use Illuminate\Http\JsonResponse;

class ErrorResponder
{
    /**
     * Create a json response with given parameters
     *
     * @param string $message
     * @param int $status
     * @param array $additional
     * @return JsonResponse
     */
    public function make(string $message, int $status, array $additional = []): JsonResponse
    {
        $errorMessages = ['message' => $message];
        if (!empty($additional)) {
            $errorMessages['errors'] = [...$additional];
        }

        return response()->json($errorMessages, $status);
    }

    /**
     * Create a json response by given error
     *
     * @param ResponseError $error
     * @param int|null $status
     * @param array $additional
     * @return JsonResponse
     */
    public function makeByError(ResponseError $error, ?int $status = null, array $additional = []): JsonResponse
    {
        $message = '';
        $statusCode = 400;

        switch ($error) {
            case ResponseError::PageNotFound:
                $message = 'Page not found';
                $statusCode = 404;
                break;
            case ResponseError::BadRequest:
                $message = 'Bad request';
                break;
            case ResponseError::ValidationFailed:
                $message = 'Invalid input data';
                break;
            case ResponseError::AuthenticationFailed:
                $message = 'Unauthenticated';
                $statusCode = 401;
                break;
            case ResponseError::Forbidden:
                $message = 'Forbidden';
                $statusCode = 403;
                break;
            case ResponseError::NotAllowedEditPublishedDocument:
                $message = 'Not allowed to edit a published document';
                break;
        }

        return $this->make($message, $status ?? $statusCode, $additional);
    }
}
