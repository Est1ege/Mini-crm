<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsManager
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->hasAnyRole(['admin', 'manager'])) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
