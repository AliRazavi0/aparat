<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Channel extends Model
{
    use SoftDeletes;

    //region model configs
    protected $table = 'channels';

    protected $fillable = ['user_id', 'name', 'info', 'banner', 'socials'];
    //endregion model configs

    //region model relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function videos()
    {
        return $this->user->videos();
    }
    //endregion model relations

    //region model setters
    public function setSocialsAttribute($value)
    {
        if (is_array($value)) $value = json_encode($value);

        $this->attributes['socials'] = $value;
    }
    //endregion model setters

    //region model getters
    public function getSocialsAttribute()
    {
        return json_decode($this->attributes['socials'], true);
    }
    //endregion model getters

    //region override model methods
    public function getRouteKeyName()
    {
        return 'name';
    }
    //endregion override model methods

}
