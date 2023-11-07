<?php

namespace App\Exceptions;

use App\Http\Requests\DocumentRequest;
use App\Services\ErrorResponder\ErrorResponder;
use App\Services\ErrorResponder\ResponseError;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
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

            $responseError = match (true) {
                $exception instanceof ForbiddenException => ResponseError::Forbidden,
                $exception instanceof ModelNotFoundException, $exception instanceof MethodNotAllowedHttpException =>
                    ResponseError::PageNotFound,
                $exception instanceof ValidationException => ResponseError::ValidationFailed,
                $exception instanceof AuthenticationException => ResponseError::AuthenticationFailed,
                default => null
            };

            if ($responseError !== null) {
                $errors = $responseError == ResponseError::ValidationFailed
                    ? $exception->validator->errors()->getMessages()
                    : [];

                return $errorResponder->makeByError($responseError, null, $errors);
            }
        }

        return parent::render($request, $exception);
    }
}
