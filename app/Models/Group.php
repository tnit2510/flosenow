<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    const COVER_PATH = 'default.jpg';
    
    const PRIVACY_ALL = 0;
    const PRIVACY_HIDE = 1;

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
        'cover',
        'privacy',
    ];

    /**
     * The attributes that should be set default value for field.
     *
     * @var array
     */
    protected $attributes = [
        'cover' => self::COVER_PATH,
        'privacy' => self::PRIVACY_ALL,
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
