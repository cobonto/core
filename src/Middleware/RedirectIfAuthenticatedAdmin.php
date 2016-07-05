<?php

namespace RmsCms\Middleware;

use Closure;

class RedirectIfAuthenticatedAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param $guest
     * @return mixed
     */
    public function handle($request, Closure $next,$guard = null)
    {
        if (\Auth::guard($guard)->check()) {
            return redirect('/admin/dashboard');
        }

        return $next($request);
    }
}
