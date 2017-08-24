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
    protected $globalPages;
    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next)
    {
        $this->globalPages = [config('app.admin_url').'.404',config('app.admin_url').'.403'];
        $actions = ['index','show','edit','store','destroy'];
        if (\Auth::guard('admin')->check())
        {
            $user = \Auth::guard('admin')->user();
            if($user->role_id != 1)
            {
                $route = $request->route()->getName();
                if(in_array($route,$this->globalPages))
                {
                    return $next($request);
                }
                $access = substr($route,strlen($this->getPrefix()),strlen($route));
                $access_array = explode('.',$access);
                // access to someone that can edit so he can view
                if(last($access_array)=='show')
                    $access = str_replace('show','edit',$access);
                // access to someone can store so if request is post he can access it
                elseif($request->method()=='POST' && !in_array(last($access_array),['store','destroy'])){
                    $access =  $access = str_replace(last($access_array),'store',$access);
                }
                if(!$user->role()->hasPermission($access))
                {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response('forbidden', adminRoute(403));
                    } else {
                        return redirect(adminRoute('403'));
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