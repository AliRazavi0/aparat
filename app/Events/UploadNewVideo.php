<?php

namespace App\Events;

use App\Video;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class UploadNewVideo
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var Video
     */
    private $video;
    /**
     * @var Request
     */
    private $request;

    /**
     * Create a new event instance.
     *
     * @param Video $video
     * @param Request $request
     */
    public function __construct(Video $video, Request $request)
    {
        $this->video = $video;
        $this->request = $request;
        Cache::forever('video-file-upload-' . $this->video->id, $this->request->video_id);
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
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return Video
     */
    public function getVideo(): Video
    {
        return $this->video;
    }
}
