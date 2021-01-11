<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discovery extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'images',
        'pattern',
        'locate',
        'Lat',
        'Lng',
    ];
    protected $hidden = [
        'id'
    ];
    public function getRouteKeyName()
    {
        return 'uuid';
    }
    //hasMany設定
    public function images()
    {
        return $this->hasMany('App\Image');
    }
}
