<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserFollowing extends Pivot
{
    protected $table = 'followers';

    protected $fillable = ['user_id1', 'user_id2'];
}
