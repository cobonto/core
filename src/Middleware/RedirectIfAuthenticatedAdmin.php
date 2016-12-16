<?php

namespace Cobonto\Middleware;

use Closure;

class RedirectIfAuthenticatedAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param string $guest
     * @return mixed
     */
    public function handle($request, Closure $next,$guard = 'admin')
    {
        if (\Auth::guard($guard)->check()) {
            return redirect(route(config('app.admin_url').'.dashboard.index'));
        }

        return $next($request);
    }
}
