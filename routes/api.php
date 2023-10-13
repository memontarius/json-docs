<?php

use Illuminate\Http\Request;
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

Route::prefix('v1')->group(function() {
    Route::post('/document', [\App\Http\Controllers\API\DocumentController::class, 'create'])->name('document.create');
    Route::get('/document/{document}', [\App\Http\Controllers\API\DocumentController::class, 'show'])->name('document.show');
    Route::patch('/document/{document}', [\App\Http\Controllers\API\DocumentController::class, 'update'])->name('document.update');
    Route::post('/document/{document}/publish', [\App\Http\Controllers\API\DocumentController::class, 'publish'])->name('document.publish');
    Route::get('/document', [\App\Http\Controllers\API\DocumentController::class, 'index'])->name('document.index');
});
