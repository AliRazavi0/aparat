<?php

namespace App\Events;

use App\User;
use App\Video;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ActiveUnregisteredUser
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {

        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
