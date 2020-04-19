<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anouncement extends Model
{
    const ANOUNCEMENT = 0;
    const UPDATE = 1;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'label',
        'thumbnail',
    ];
}
