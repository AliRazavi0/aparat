<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function follow(User $user, User $otherUser)
    {
        return $user->id != $otherUser->id;
    }

    public function unfollow(User $user, User $otherUser)
    {
        return ($user->id != $otherUser->id) &&
            ($user->followings()->where('user_id2', $otherUser->id)->count());
    }

    public function seeFollowingList(User $user)
    {
        return true;
    }

    public function list(User $user)
    {
        return $user->isAdmin();
    }

    public function update(User $user)
    {
        return $user->isAdmin();
    }

    public function resetPassword(User $user, User $otherUser)
    {
        return $user->isAdmin();
    }
}
