<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'cat_id',
        'user_id',
        'message',
    ];

    //belongsTo設定
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // protected function asDateTime($value) {
    //     $date = parent::asDateTime($value);
    //     $time_zone = null;
    //     if(\Auth::check()) {
    //         $config = \Auth::user()->config; // ユーザ固有の設定を持つ自作クラスUserConfigモデル
    //         if($config) {
    //             $time_zone = $config->time_zone?:$time_zone; // UserConfigのtime_zoneが設定されていればそちらを使う
    //         }
    //     }
    //     if($time_zone) {
    //         $date->timezone($time_zone); // timezoneメソッドでCarbonのタイムゾーンを上書き
    //     }
    //     return $date;
    // }
    // public function fromDateTime($value)
    // {
    //     // DBに保存する際に時刻がタイムゾーンの影響を受けてしまうため、デフォルトのタイムゾーンに戻す処理を挟む
    //     return empty($value) ? $value : $this->asDateTime($value)->timezone(config('app.timezone'))->format(
    //         $this->getDateFormat()
    //     );
    // }
}
