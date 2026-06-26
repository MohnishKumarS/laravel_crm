<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      $token = $request->bearerToken();

    if (!$token) {
        return response()->json([
            'message' => 'Token missing'
        ], 401);
    }

    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json([
            'message' => 'Invalid token'
        ], 401);
    }

    $expiration = config('sanctum.expiration');

    if (
        $expiration &&
        Carbon::parse($accessToken->created_at)
            ->addMinutes($expiration)
            ->isPast()
    ) {
        return response()->json([
            'message' => 'Token expired'
        ], 401);
    }

    return $next($request);
    }
}
