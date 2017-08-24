<?php

namespace Cobonto\Controllers\Admin;

use App\Http\Controllers\Controller;
use Cobonto\Classes\Admin;
use Cobonto\Events\AdminLoggedIn;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;
    protected $loginView = 'admin.auth.login';
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectPath  = false;
    protected $redirectAfterLogout  = false;
    protected $guard = 'admin';
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $admin_url = config('app.admin_url');
        $this->redirectPath = $admin_url.'/dashboard';
        $this->redirectAfterLogout = $admin_url.'/login';
        $this->middleware('guest.admin', ['except' => ['logout']]);
    }
    public function getCredentials($request)
    {
        $credentials = $request->only($this->loginUsername(), 'password');

        return array_add($credentials, 'active', '1');
    }
    protected function authenticated($request,$user)
    {
        \Event::fire(new AdminLoggedIn(\Auth::guard($this->getGuard())->user()));
        return redirect()->to($this->redirectPath());
    }
}
