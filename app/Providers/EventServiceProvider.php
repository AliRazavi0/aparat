<?php

namespace App\Providers;

use App\Events\ActiveUnregisteredUser;
use App\Events\DeleteVideo;
use App\Events\UploadNewVideo;
use App\Events\VisitVideo;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Laravel\Passport\Events\AccessTokenCreated;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UploadNewVideo::class => [
            'App\Listeners\ProcessUploadedVideo'
        ],
        DeleteVideo::class => [
            'App\Listeners\DeleteVideoData'
        ],
        VisitVideo::class => [
            'App\Listeners\AddVisitedVideoLogToVideoViewsTable'
        ],
        AccessTokenCreated::class => [
            'App\Listeners\ActiveUnregisteredUserAfterLogin'
        ],
        ActiveUnregisteredUser::class => [
            //TODO کارهایی که بعد از فعالسازی مجدد کاربر باید انجام شود
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
