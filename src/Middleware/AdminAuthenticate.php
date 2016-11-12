<?php
/**
 * Created by PhpStorm.
 * User: sharif ahrari
 * Date: 6/30/2016
 * Time: 3:13 AM
 */

namespace Cobonto\Middleware;

use Closure;
class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\Auth::check())
        {
            if(!\Auth::user()->is_admin)
            {

                if ($request->ajax() || $request->wantsJson()) {
                    return response('Unauthorized.', 401);
                } else {
                    return redirect()->guest(config('app.admin_url').'/login');
                }
            }
        }
        else
        {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest(config('app.admin_url').'/login');
            }
        }
        return $next($request);
    }
}