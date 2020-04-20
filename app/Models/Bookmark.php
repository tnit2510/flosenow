<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bookmark extends Model
{
    use SoftDeletes;

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
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
