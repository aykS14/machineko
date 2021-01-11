<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'cat_id',
        'user_id',
        'filename',
    ];

    //belongsTo設定
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function discovery()
    {
        return $this->belongsTo('App\Discovery');
    }
}
