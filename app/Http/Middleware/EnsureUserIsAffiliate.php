<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAffiliate
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'affiliate' || !$user->affiliate) {
            abort(403, 'This area is only available to affiliate accounts.');
        }

        return $next($request);
    }
}
