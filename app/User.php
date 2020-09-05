<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    //region types
    const TYPE_ADMIN = 'admin';
    const TYPE_USER = 'user';
    const TYPES = [self::TYPE_ADMIN, self::TYPE_USER];
    //endregion types

    //region model configs
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'mobile', 'email', 'name', 'password', 'avatar', 'website', 'verify_code', 'verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'verify_code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'verified_at' => 'datetime',
    ];
    //endregion model configs

    //region custom methods
    /**
     * پیدا کردن کاربر برای ورود به سیستم از طریق موبایل یا ایمیل
     *
     * @param $username
     * @return mixed
     */
    public function findForPassport($username)
    {
        $user = static::withTrashed()
            ->where('mobile', $username)
            ->orWhere('email', $username)
            ->first();
        return $user;
    }

    public function isAdmin()
    {
        return $this->type === User::TYPE_ADMIN;
    }

    public function isBaseUser()
    {
        return $this->type === User::TYPE_USER;
    }

    public function follow(User $user)
    {
        return UserFollowing::create([
            'user_id1' => $this->id,
            'user_id2' => $user->id,
        ]);
    }

    public function unfollow(User $user)
    {
        return UserFollowing::where([
            'user_id1' => $this->id,
            'user_id2' => $user->id,
        ])->delete();
    }
    //endregion custom methods

    //region setters
    public function setMobileAttribute($value)
    {
        $this->attributes['mobile'] = to_valid_mobile_number($value);
    }
    //endregion setters

    //region getters
    public function getAvatarAttribute()
    {
        $avatar = $this->attributes['avatar'];

        if (empty($avatar)) {
            if (!empty($this->channel) && !empty($this->channel->banner)) {
                $avatar = $this->channel->banner;
            }
            else{
                $avatar = asset('img/avatar.png');
            }
        }

        return $avatar;
    }
    //endregion getters

    //region relations
    public function channel()
    {
        return $this->hasOne(Channel::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function favouriteVideos()
    {
        return $this->hasManyThrough(
            Video::class,
            VideoFavourite::class,
            'user_id', // republishes_video.user_id
            'id', // video.id
            'id', // user.id
            'video_id' // republishes_video.video_id
        );
    }

    public function channelVideos()
    {
        return $this->hasMany(Video::class)
            ->selectRaw('*, 0 as republished');
    }

    public function republishedVideos()
    {
        return $this->hasManyThrough(
            Video::class,
            VideoRepublish::class,
            'user_id', // republishes_video.user_id
            'id', // video.id
            'id', // user.id
            'video_id' // republishes_video.video_id
        )
            ->selectRaw('videos.*, 1 as republished');
    }

    public function videos()
    {
        return $this->channelVideos()
            ->union($this->republishedVideos());
    }

    public function followings()
    {
        return $this->hasManyThrough(
            User::class,
            UserFollowing::class,
            'user_id1',
            'id',
            'id',
            'user_id2'
        );
    }

    public function followers()
    {
        return $this->hasManyThrough(
            User::class,
            UserFollowing::class,
            'user_id2',
            'id',
            'id',
            'user_id1'
        );
    }

    public function views()
    {
        return $this
            ->belongsToMany(Video::class, 'video_views')
            ->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    //endregion relations

    //region override model methods
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            $user->channelVideos()->delete();
            $user->playlists()->delete();
        });

        static::restoring(function ($user) {
            $user->channelVideos()->restore();
            $user->playlists()->restore();
        });
    }
    //endregion override model methods
}
