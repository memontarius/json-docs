<?php

use App\Http\Controllers\API\DocumentController;
use App\Http\Controllers\API\UserController;
use App\Services\ErrorResponder\ResponseError;
use App\Services\ErrorResponder\ErrorResponder;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    Route::post('/login', [UserController::class, 'login'])->name('login');

    Route::prefix('document')->group(function () {

        Route::middleware('auth.optional:sanctum')->group(function () {
            Route::get('/{document}', [DocumentController::class, 'show']);
            Route::get('/', [DocumentController::class, 'index']);
        });

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', [DocumentController::class, 'store']);
            Route::patch('/{document}', [DocumentController::class, 'update']);
            Route::post('/{document}/publish', [DocumentController::class, 'publish']);
        });
    });
});

Route::any('{any}', function (ErrorResponder $errorResponder, $any) {
    $responseErrorParam = request()->input('response_error');

    $responseError = $responseErrorParam ?
        ResponseError::tryFrom($responseErrorParam) ?? ResponseError::PageNotFound :
        ResponseError::PageNotFound;

    return $errorResponder->makeByError($responseError);
})->where('any', '.*')->name('fallback');

/* Not working with POST methods
Route::fallback(function (ErrorResponder $errorResponder) {
    return $errorResponder->makeByError(ResponseError::PageNotFound);
})->name('fallback');*/
