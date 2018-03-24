<?php
namespace Cobonto\Events;


use Cobonto\Classes\Admin;
use Illuminate\Queue\SerializesModels;

class AdminLoggedIn
{
    use SerializesModels;
    /** @var  Admin */
    public $admin;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($admin)
    {
        $this->admin = $admin;
    }

}
