<?php

namespace App\Exceptions;

use App\Services\ErrorResponder\ErrorResponder;
use App\Services\ErrorResponder\ResponseError;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Exception|Throwable $exception)
    {

        if ($request->is('api/*')) {
            $errorResponder = App::make(ErrorResponder::class);

            if ($exception instanceof ModelNotFoundException) {
                return $errorResponder->makeByError(ResponseError::PageNotFound);
            } elseif ($exception instanceof ValidationException) {
                $errors = $exception->validator->errors()->getMessages();
                return $errorResponder->makeByError(ResponseError::ValidationFailed, null, $errors);
            } elseif ($exception instanceof MethodNotAllowedHttpException) {
                return $errorResponder->makeByError(ResponseError::PageNotFound);
            } elseif ($exception instanceof AuthenticationException) {
                return $errorResponder->makeByError(ResponseError::AuthenticationFailed);
            }
        }

        return parent::render($request, $exception);
    }
}
