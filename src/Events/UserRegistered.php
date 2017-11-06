<?php
/**
 * Created by PhpStorm.
 * User: fara
 * Date: 5/20/2017
 * Time: 8:12 PM
 */

namespace Cobonto\Events;



use Cobonto\Classes\User;

class UserRegistered extends Event
{
    /**
     * @var User
     */
    public $user;

    public $password;



    public function __construct($user,$password)
    {
        $this->user = User::find($user);

        $this->password = $password;
    }
}