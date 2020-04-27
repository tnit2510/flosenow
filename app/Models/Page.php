<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    const AVATAR_PATH = 'default.jpg';
    const COVER_PATH = 'default.jpg';

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
        'name',
        'slug',
        'avatar',
        'cover',
    ];

    /**
     * The attributes that should be set default value for field.
     *
     * @var array
     */
    protected $attributes = [
        'avatar' => self::AVATAR_PATH,
        'cover' => self::COVER_PATH,
    ];

    public function posts()
    {
        return $this->morphMany(Post::class, 'postable');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function visits()
    {
        return visits($this);
    }
}
