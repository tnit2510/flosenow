<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasRoles, SoftDeletes;

    const AVATAR_PATH = '/users/avatars/default.jpg';
    const COVER_PATH  = '/users/covers/default.jpg';
    const BIO         = 'I Love Flosenow o((>ω< ))o';
    const ROLE_GROUP  = 0;
    const ROLE_PAGE   = 0;

    protected $guard_name = 'api';

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'username';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'email',
        'avatar',
        'cover',
        'bio',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be set default value for field.
     *
     * @var array
     */
    protected $attributes = [
        'avatar' => self::AVATAR_PATH,
        'cover' => self::COVER_PATH,
        'bio' => self::BIO,
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class)
            ->withPivot([
                'role',
                'join_at',
            ]);
    }

    public function messengers()
    {
        return $this->hasMany(Messenger::class);
    }

    public function pages()
    {
        return $this->belongsToMany(Page::class)
            ->withPivot('role');
    }

    public function posts()
    {
        return $this->morphMany(Post::class, 'postable');
    }

    public function titles()
    {
        return $this->belongsToMany(Title::class)
            ->withPivot('receive_at');
    }
}
