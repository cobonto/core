<?php
namespace Cobonto\Classes;

use Cobonto\Classes\Roles\Role;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use LaravelArdent\Ardent\Ardent;

class Admin extends Ardent implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    protected $table ='admins';
    public $autoHydrateEntityFromInput = true;    // hydrates on new entries' validation
    public $forceEntityHydrationFromInput = true; // hydrates whenever validation is called
    public $autoPurgeRedundantAttributes = true;
    public static $passwordAttributes  = ['password'];
    public $autoHashPasswordAttributes = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname','lastname', 'lang','email','remember_token','password', 'active','password_confirmation','role_id'
    ];
    public static $rules = [
        'firstname' => 'required|alpha_spaces|between:3,255',
        'lastname' => 'required|alpha_spaces|between:3,255',
        'lang' => 'required|alpha',
        'email' => 'required|email',
        'active' => 'required|boolean',
        'password' => 'between:6,300',
        'role_id' => 'required|numeric',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $guarded = [
        'password'
    ];

    /**
     *  Get user by email
     * @param string $email
     * @param bool|false $returId
     * @return bool|mixed|static
     */
    public static function getByEmail($email,$returId=false)
    {
        $result = Admin::where('email',$email)->first();
        if($result)
        {
            if($returId)
                return (int)$result->id;
            else
                return true;
        }
        else
            return false;

    }

    /**
     * Get role object
     * @return Role
     */
    public function role()
    {
        return Role::find($this->role_id);
    }
    public function setLocale()
    {
        app('translator')->setLocale($this->lang);
        if($this->lang=='fa')
            config(['app.rtl'=>1]);
        else
            config(['app.rtl'=>0]);
    }
}
