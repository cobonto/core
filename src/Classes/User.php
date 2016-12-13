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

class User extends Ardent implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
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
        'firstname','lastname', 'email', 'password', 'active','password_confirmation','role_id','last_login'
    ];
    public static $rules = [
        'firstname' => 'required|alpha|between:3,255',
        'lastname' => 'required|alpha|between:3,255',
        'email' => 'required|email',
        'active' => 'required|boolean',
        'password' => 'between:6,20|confirmed',
        'password_confirmation' => 'between:6,20',
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
        'password', 'remember_token',
    ];

    /**
     *  Get user by email
     * @param string $email
     * @param bool|false $returId
     * @return bool|mixed|static
     */
    public static function getByEmail($email,$returId=false)
    {
        $result = \DB::table('users')->where('email',$email)->first();
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
    /**
     * The roles that belong to the user.
     */
}
