<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequirePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->user()?->force_password_change
            && ! $request->routeIs('force-password.*')
        ) {
            return to_route('force-password.edit');
        }

        return $next($request);
    }
}
