<?php

namespace App\Events;

use App\Video;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DeleteVideo
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var Video
     */
    private $video;

    /**
     * Create a new event instance.
     *
     * @param Video $video
     */
    public function __construct(Video $video)
    {
        //
        $this->video = $video;
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
     * @return Video
     */
    public function getVideo(): Video
    {
        return $this->video;
    }
}
