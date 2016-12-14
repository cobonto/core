<?php
namespace Cobonto\Events;


use App\Events\Event;
use Cobonto\Classes\Admin;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AdminLoggedIn extends Event
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
