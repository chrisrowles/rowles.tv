<?php

namespace Rowles\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Rowles\Models\User;

class EnsureUserIsAdministrator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->role !== User::ADMINISTRATOR) {
            abort(403);
        }

        return $next($request);
    }
}
