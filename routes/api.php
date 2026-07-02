<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\FormApiController;
use App\Http\Controllers\Api\PostApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['check.token','auth:sanctum']);

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::prefix('forms')->group(function () {
    Route::get('/{slug}', [FormApiController::class, 'show']);
    Route::post('/{slug}/submit', [FormApiController::class, 'submit']);
    
});

Route::prefix('posts')->group(function () {
    Route::get('/', [PostApiController::class, 'index']);
    Route::get('/{slug}', [PostApiController::class, 'show']);
});

Route::prefix('campaigns')->group(function () {
    Route::get('/current', [CampaignController::class, 'current']);   // public
    Route::get('/', [CampaignController::class, 'index']);
    Route::get('/{campaign}', [CampaignController::class, 'show']);
    Route::post('/', [CampaignController::class, 'store']);
    Route::post('/{campaign}', [CampaignController::class, 'update']); // send _method=PUT in body for image uploads
    Route::put('/{campaign}', [CampaignController::class, 'update']);
    Route::delete('/{campaign}', [CampaignController::class, 'destroy']);
});
