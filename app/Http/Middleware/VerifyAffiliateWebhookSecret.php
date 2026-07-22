<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyAffiliateWebhookSecret
{
    public function handle(Request $request, Closure $next)
    {
        $expected = config('services.affiliate_webhook.secret');

        if (!$expected || $request->header('X-Webhook-Secret') !== $expected) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}