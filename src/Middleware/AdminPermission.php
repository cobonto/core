<?php
/**
 * Created by PhpStorm.
 * User: Sharif
 * Date: 11/5/2016
 * Time: 11:23 AM
 */

namespace Cobonto\Middleware;
use Closure;
use Illuminate\Http\Request;

class AdminPermission
{
    protected $globalPages= ['admin.403','admin.404'];
    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next)
    {
        if (\Auth::check())
        {
            $user = \Auth::user();
            if($user->is_admin && $user->role_id != 1)
            {
                $route = $request->route()->getName();
                if(in_array($route,$this->globalPages))
                     return $next($request);;
                $access = substr($route,strlen($this->getPrefix()),strlen($route));
                if(!$user->role()->hasPermission($access))
                {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response('forbidden', 403);
                    } else {
                        return redirect(route('admin.403'));
                    }
                }
            }
        }
        return $next($request);
    }
    protected function getPrefix()
    {
        return config('app.admin_url').'.';
    }
}