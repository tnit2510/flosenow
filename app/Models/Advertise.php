<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advertise extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'expiry_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'expiry_at' => 'datetime',
    ];

    public function setExpiryAtAttribute($input)
    {
        $this->attributes['expiry_at'] = Carbon::createFromFormat(config('app.date_format'), $input)->format('Y-m-d');
    }

    public function posts()
    {
        return $this->hasOne(Post::class);
    }
}
