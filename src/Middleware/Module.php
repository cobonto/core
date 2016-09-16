<?php
/**
 * Created by PhpStorm.
 * User: sharif ahrari
 * Date: 6/30/2016
 * Time: 3:13 AM
 */

namespace Cobonto\Middleware;
use Closure;

class Module
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        return $next($request);
    }
}