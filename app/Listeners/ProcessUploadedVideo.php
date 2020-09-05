<?php

namespace App\Listeners;

use App\Events\UploadNewVideo;
use App\Jobs\ConvertAndAddWaterMarkToUploadedVideoJob;
use FFM;

class ProcessUploadedVideo
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
     * @param  UploadNewVideo $event
     * @return void
     */
    public function handle(UploadNewVideo $event)
    {
        ConvertAndAddWaterMarkToUploadedVideoJob::dispatch(
            $event->getVideo(),
            $event->getRequest()->video_id,
            $event->getRequest()->enable_watermark
        );
    }
}
