<?php

namespace App;

use App\Notifications\PasswordResetMultiLang;
use App\Notifications\VerifyEmailMultiLang;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
//use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
//use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    // use MustVerifyEmail, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
     * 登録確認メールの送信
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailMultiLang);
    }
    
    /**
     * パスワード再設定メールの送信
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetMultiLang($token));
    }

    // //primaryKeyの変更
    // protected $primaryKey = "user_id";

    //hasMany設定
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
