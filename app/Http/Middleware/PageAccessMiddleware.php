<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageAccessMiddleware
{
    public function handle(Request $request, Closure $next, string $key)
    {
        if (!Auth::check() || !Auth::user()->hasPageAccess($key)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
