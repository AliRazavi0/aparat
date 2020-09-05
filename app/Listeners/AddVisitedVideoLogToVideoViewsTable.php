<?php

namespace App\Listeners;

use App\Events\VisitVideo;
use App\VideoView;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class AddVisitedVideoLogToVideoViewsTable
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  VisitVideo $event
     * @return void
     */
    public function handle(VisitVideo $event)
    {
        try {
            $video = $event->getVideo();
            $conditions = [
                'user_id' => auth('api')->id(),
                'video_id' => $video->id,
                ['created_at', '>', now()->subDays(1)]
            ];
            $clientIp = client_ip();

            if (!auth('api')->check()) {
                $conditions['user_ip'] = $clientIp;
            }

            if (!VideoView::where($conditions)->count()) {
                VideoView::create([
                    'user_id' => auth('api')->id(),
                    'video_id' => $video->id,
                    'user_ip' => $clientIp
                ]);
            }
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }
}
