<?php

namespace Cobonto\Controllers\Admin;

use App\Http\Controllers\Controller;
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
    protected $redirectPath  = 'admin/dashboard';
    protected $redirectAfterLogout  = 'admin/login';
    protected $guard = null;
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest.admin', ['except' => ['logout']]);
    }
    public function getCredentials($request)
    {
        $credentials = $request->only($this->loginUsername(), 'password');

        $credentials =  array_add($credentials, 'active', '1');
        return array_add($credentials, 'is_admin', '1');
    }
}
