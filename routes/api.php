<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\FormApiController;
use App\Http\Controllers\Api\HomeHeroController;
use App\Http\Controllers\Api\PostApiController;
use App\Http\Controllers\Api\ReferralController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\VisitorController;
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

// Analytics
Route::post('/visitors',[VisitorController::class,'index']);
Route::post('/visitors/shop',[VisitorController::class,'shopVisitors']);

Route::prefix('campaigns')->group(function () {
    Route::get('/current', [CampaignController::class, 'current']);   // public
    Route::get('/', [CampaignController::class, 'index']);
    Route::get('/{campaign}', [CampaignController::class, 'show']);
    Route::post('/', [CampaignController::class, 'store']);
    Route::post('/{campaign}', [CampaignController::class, 'update']); // send _method=PUT in body for image uploads
    Route::put('/{campaign}', [CampaignController::class, 'update']);
    Route::delete('/{campaign}', [CampaignController::class, 'destroy']);
});
Route::get('home-hero', [HomeHeroController::class, 'active']);


// Public - no auth required, called on every storefront pageview / checkout
Route::post('referral/track', [ReferralController::class, 'track']);
Route::post('referral/apply-code', [ReferralController::class, 'applyCode']);

// Authenticated customer/affiliate endpoints
Route::middleware('auth:sanctum')->prefix('affiliate')->group(function () {
    // Route::post('register', [AuthController::class, 'register']);
    Route::post('affiliate/register', [AuthController::class, 'register']);
    Route::get('me', [AuthController::class, 'me']);
    Route::get('dashboard', [DashboardController::class, 'stats']);
    Route::get('commissions', [DashboardController::class, 'commissions']);
    Route::get('payouts', [DashboardController::class, 'payouts']);
    Route::get('link', [DashboardController::class, 'link']);
});
Route::post('affiliate/register-new', [AuthController::class, 'registerNew']);
Route::get('referral/current', [ReferralController::class, 'current']);
Route::middleware('affiliate.webhook.secret')->group(function () {
    Route::post('affiliate/webhooks/order-paid', [WebhookController::class, 'orderPaid']);
    Route::post('affiliate/webhooks/order-refunded', [WebhookController::class, 'orderRefunded']);
});
