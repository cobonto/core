<?php
namespace App;

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
    protected $attributes = array(
        'is_admin' => '0',
    );
    public static $passwordAttributes  = ['password'];
    public $autoHashPasswordAttributes = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'active', 'is_admin','password_confirmation'
    ];
    public static $rules = [
        'name' => 'required|alpha|between:3,255',
        'email' => 'required|email',
        'active' => 'required|boolean',
        'is_admin' => 'required|numeric',
        'password' => 'required|between:6,20|confirmed',
        'password_confirmation' => 'between:9,20',
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
        if($returId)
            return (int)$result->id;
        else
            return (bool)$result->id;
    }
}
