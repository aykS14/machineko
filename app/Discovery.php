<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discovery extends Model
{
    protected $fillable = [
        'user_id',
        'images',
        'pattern',
        'locate'
    ];
}
