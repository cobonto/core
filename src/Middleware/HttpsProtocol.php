<?php
/**
 * Created by PhpStorm.
 * User: fara
 * Date: 1/13/2017
 * Time: 5:21 PM
 */

namespace Cobonto\Middleware;

use Closure;
use Illuminate\Http\Request;

class HttpsProtocol
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if(config('app.secure_protocol'))
        if (!$request->secure()) {
            $request->setTrustedProxies([$request->getClientIp()]);
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}