<?php
namespace Cobonto\Listeners\UserRegistered;

use Cobonto\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Created by PhpStorm.
 * User: fara
 * Date: 5/20/2017
 * Time: 8:39 PM
 */
class WelcomeEmail implements ShouldQueue
{
    use InteractsWithQueue;
    public function __construct()
    {
    }

    public function handle(UserRegistered $event)
    {
        if (true) {
            $this->release(30);
        }
    }
}