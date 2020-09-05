<?php

namespace App\Providers;

use App\Comment;
use App\Playlist;
use App\Policies\CommentPolicy;
use App\Policies\PlaylistPolicy;
use App\Policies\UserPolicy;
use App\Policies\VideoPolicy;
use App\User;
use App\Video;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Video::class => VideoPolicy::class,
        Comment::class => CommentPolicy::class,
        Playlist::class => PlaylistPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //اضافه کردن زمان انقضا برای توکن و رفرش توکن
        Passport::tokensExpireIn(now()->addMinutes(config('auth.token_expiration.token')));
        Passport::refreshTokensExpireIn(now()->addMinutes(config('auth.token_expiration.refresh_token')));

        $this->registerGates();
    }

    private function registerGates()
    {
        Gate::before(function ($user, $ability) {
            if ($user->isAdmin()) {
                return true;
            }
        });
    }
}
