<?php

namespace Rowles\Http\Middleware;

use Closure;
use Rowles\Models\User;
use Illuminate\Http\Request;

class EnsureUserIsAdministrator
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->role !== User::ADMINISTRATOR) {
            abort(403);
        }

        return $next($request);
    }
}
