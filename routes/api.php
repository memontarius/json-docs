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
        Route::post('/', [DocumentController::class, 'store']);
        Route::get('/{document}', [DocumentController::class, 'show']);
        Route::patch('/{document}', [DocumentController::class, 'update']);
        Route::post('/{document}/publish', [DocumentController::class, 'publish']);
        Route::get('/', [DocumentController::class, 'index'])->name('document.index');
    });
});

Route::any('{any}', function (ErrorResponder $errorResponder) {
    return $errorResponder->makeByError(ResponseError::PageNotFound);
})->where('any', '.*')->name('fallback');

/* Not working with POST methods
Route::fallback(function (ErrorResponder $errorResponder) {
    return $errorResponder->makeByError(ResponseError::PageNotFound);
})->name('fallback');*/
