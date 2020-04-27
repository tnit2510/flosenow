<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Socialite extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider',
        'provider_id',
    ];
}
