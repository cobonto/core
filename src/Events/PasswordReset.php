<?php
/**
 * Created by PhpStorm.
 * User: fara
 * Date: 5/20/2017
 * Time: 8:12 PM
 */

namespace Cobonto\Events;


use App\Events\Event;
use Cobonto\Classes\User;

class PasswordReset extends Event
{
    /**
     * @var User
     */
    public $user;

    public $password;



    public function __construct($user_id,$password)
    {
        $this->user = User::find($user_id);

        $this->password = $password;
    }
}