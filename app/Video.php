<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use SoftDeletes;

    //region states
    const STATE_PENDING = 'pending'; //در صف برای پردازش
    const STATE_CONVERTED = 'converted'; //تبدیل انجام شده
    const STATE_ACCEPTED = 'accepted'; //مورد تایید هست و در لیست نمایش قرار میگیرد
    const STATE_BLOCKED = 'blocked'; //محتوای ویدیو مناسب نبوده و نمایش داده نخواهد شد
    const STATES = [self::STATE_PENDING, self::STATE_CONVERTED, self::STATE_ACCEPTED, self::STATE_BLOCKED];
    //endregion states

    //region model configs
    protected $table = 'videos';

    protected $fillable = [
        'title', 'user_id', 'category_id', 'channel_category_id',
        'slug', 'info', 'duration', 'banner', 'publish_at',
        'enable_comments', 'state'
    ];

    protected $with = ['playlist', 'tags'];

    protected $appends = ['likeCount', 'age'];
    //endregion model configs

    //region relations
    public function playlist()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_videos');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'video_tags');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function viewers()
    {
        //TODO افزودن دیتای کاربرهایی که هنوز لاگین نکردن به آمار viewers
        return $this
            ->belongsToMany(User::class, 'video_views')
            ->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function related()
    {
        return static::selectRaw('COUNT(*) related_tags, videos.*')
            ->leftJoin('video_tags', 'videos.id', '=', 'video_tags.video_id')
            ->whereRaw('videos.id != ' . $this->id)
            ->whereRaw("videos.state = '" . self::STATE_ACCEPTED . "'")
            ->whereIn(DB::raw('video_tags.tag_id'), function ($query) {
                $query->selectRaw('video_tags.tag_id')
                    ->from('videos')
                    ->leftJoin('video_tags', 'videos.id', '=', 'video_tags.video_id')
                    ->whereRaw('videos.id=' . $this->id);
            })
            ->groupBy(DB::raw('videos.id'))
            ->orderBy('related_tags', 'desc');
    }

    public function republishes()
    {
        return $this->hasMany(VideoRepublish::class, 'video_id', 'id');
    }

    public function likes()
    {
        return $this
            ->hasMany(VideoFavourite::class);
    }
    //endregion relations

    //region getters
    public function getVideoLinkAttribute()
    {
        if ($cachedVideoId = Cache::get('video-file-upload-' . $this->id)) {
            return Storage::disk('videos')
                ->url('tmp/' . $cachedVideoId);
        }

        return Storage::disk('videos')
            ->url($this->user_id . '/' . $this->slug . '.mp4');
    }

    public function getBannerLinkAttribute()
    {
        return $this->banner
            ? Storage::disk('videos')
                ->url($this->user_id . '/' . $this->slug . '-banner') . '?v=' . optional($this->updated_at)->timestamp
            : asset('/img/no-video.jpg');
    }

    public function getLikeCountAttribute()
    {
        return $this->likes()->count();
    }

    public function getAgeAttribute()
    {
        return Carbon::now()->diffInDays($this->created_at);
    }
    //endregion getters

    //region override model methods
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function toArray()
    {
        $data = parent::toArray();

        $data['link'] = $this->video_link;
        $data['banner_link'] = $this->banner_link;
        $data['views'] = VideoView::where(['video_id' => $this->id])->count();

        return $data;
    }
    //endregion override model methods

    //region custom methods
    public function isInState($state)
    {
        return $this->state === $state;
    }

    public function isPending()
    {
        return $this->isInState(self::STATE_PENDING);
    }

    public function isAccepted()
    {
        return $this->isInState(self::STATE_ACCEPTED);
    }

    public function isConverted()
    {
        return $this->isInState(self::STATE_CONVERTED);
    }

    public function isBlocked()
    {
        return $this->isInState(self::STATE_BLOCKED);
    }

    public function isRepublished($userId = null)
    {
        if ($userId) {
            return (bool)$this->republishes()->where('user_id', $userId)->count();
        }

        return (bool)$this->republishes()->count();
    }
    //endregion custom methods

    //region custom static methods
    public static function whereNotRepublished()
    {
        return static::whereRaw('id not in (select video_id from video_republishes)');
    }

    public static function whereRepublished()
    {
        return static::whereRaw('id in (select video_id from video_republishes)');
    }

    /**
     * @param $userId
     * @return Builder
     */
    public static function views($userId)
    {
        return static::where('videos.user_id', $userId)
            ->join('video_views', 'videos.id', '=', 'video_views.video_id');
    }

    /**
     * @param $userId
     * @return Builder
     */
    public static function channelComments($userId)
    {
        return static::where('videos.user_id', $userId)
            ->join('comments', 'videos.id', '=', 'comments.video_id');
    }
    //endregion custom static methods
}
