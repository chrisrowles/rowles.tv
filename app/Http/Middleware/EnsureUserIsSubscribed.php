<?php

namespace Rowles\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class EnsureUserIsSubscribed
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
        if (!Auth::user()->subscribed()) {
            return redirect()->route('subscribe');
        }

        return $next($request);
    }
}
