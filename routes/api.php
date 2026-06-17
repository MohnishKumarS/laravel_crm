<?php

use App\Http\Controllers\Api\FormApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('forms')->group(function () {
    Route::get('/{slug}', [FormApiController::class, 'show']);
    Route::post('/{slug}/submit', [FormApiController::class, 'submit']);
});