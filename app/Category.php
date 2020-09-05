<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    //region model configs
    protected $table = 'categories';

    protected $fillable = ['title', 'icon', 'banner', 'user_id'];
    //endregion model configs

    //region relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
    //endregion relations
}
