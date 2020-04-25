<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    const ALL = 0;
    const ONLY_ME = 1;
    const FOLLOW_ME = 2;

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail',
        'privacy',
    ];

    /**
     * The attributes that should be set default value for field.
     *
     * @var array
     */
    protected $attributes = [
        'privacy' => self::ALL,
    ];

    public function advertise()
    {
        return $this->belongsTo(Advertise::class);
    }

    public function bookmarks()
    {
        return $this->belongsToMany(Bookmark::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function hashtags()
    {
        return $this->belongsToMany(Hashtag::class);
    }

    public function postable()
    {
        return $this->morphTo();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'reactions')->withPivot('type');
    }

    public function visits()
    {
        return visits($this);
    }
}
