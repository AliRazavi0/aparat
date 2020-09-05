<?php

namespace App\Jobs;

use App\Video;
use FFM;
use FFMpeg\Filters\Video\CustomFilter;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ConvertAndAddWaterMarkToUploadedVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Video
     */
    private $video;

    /**
     * @var string
     */
    private $videoId;

    /**
     * @var int
     */
    private $userId;
    /**
     * @var bool
     */
    private $addWatermark;

    /**
     * Create a new job instance.
     *
     * @param Video $video
     * @param string $videoId
     * @param bool $addWatermark
     */
    public function __construct(Video $video, string $videoId, bool $addWatermark)
    {
        $this->videoId = $videoId;
        $this->video = $video;
        $this->addWatermark = $addWatermark;
        $this->userId = auth()->id();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $uploadedVideoPath = '/tmp/' . $this->videoId;

        if (!Video::where('id', $this->video->id)->count()) {
            Storage::disk('videos')->delete($uploadedVideoPath);
            return;
        }

        $videoUploaded = FFM::fromDisk('videos')->open($uploadedVideoPath);
        $format = new X264('libmp3lame');

        if ($this->addWatermark) {
            $filter = new CustomFilter(
                "drawtext=text='http\\://test.net': fontcolor=blue: fontsize=24:
                        box=1: boxcolor=white@0.5: boxborderw=5:
                        x=10: y=(h - text_h - 10)");
            $videoUploaded = $videoUploaded->addFilter($filter);
        }

        /** @var Media $videoFile */
        $videoFile = $videoUploaded->export()
            ->toDisk('videos')
            ->inFormat($format);

        $videoFile->save($this->userId . '/' . $this->video->slug . '.mp4');

        $this->video->duration = $videoUploaded->getDurationInSeconds();
        $this->video->state = Video::STATE_CONVERTED;
        $this->video->save();

        Storage::disk('videos')->delete($uploadedVideoPath);
        Cache::forget('video-file-upload-' . $this->video->id);
    }
}
