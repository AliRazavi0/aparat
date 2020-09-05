<?php

namespace App\Policies;

use App\User;
use App\Video;
use App\VideoFavourite;
use App\VideoRepublish;
use Illuminate\Auth\Access\HandlesAuthorization;

class VideoPolicy
{
    use HandlesAuthorization;

    public function changeState(User $user, Video $video = null)
    {
        return $user->isAdmin();
    }

    public function republish(User $user, Video $video = null)
    {
        return $video && $video->isAccepted() &&
            (
                $video->user_id != $user->id &&
                VideoRepublish::where([
                    'user_id' => $user->id,
                    'video_id' => $video->id
                ])->count() < 1
            );
    }

    public function like(User $user = null, Video $video = null)
    {
        if ($video && $video->isAccepted()) {
            $conditions = [
                'video_id' => $video->id,
                'user_id' => $user ? $user->id : null
            ];

            if (empty($user)) {
                $conditions['user_ip'] = client_ip();
            }

            return VideoFavourite::where($conditions)->count() == 0;
        }

        return false;
    }

    public function unlike(User $user = null, Video $video = null)
    {
        $conditions = [
            'video_id' => $video->id,
            'user_id' => $user ? $user->id : null
        ];

        if (empty($user)) {
            $conditions['user_ip'] = client_ip();
        }

        return VideoFavourite::where($conditions)->count();
    }

    public function seeLikedList(User $user, Video $video = null)
    {
        return true;
    }

    public function delete(User $user, Video $video)
    {
        $result = $user->id === $video->user_id;

        if (!$result) {
            $result = $video->isRepublished($user->id);
        }

        return $result;
    }

    public function update(User $user, Video $video)
    {
        return $user->id === $video->user_id;
    }

    public function showStatistics(User $user, Video $video){
        return $user->id === $video->user_id;
    }
}
