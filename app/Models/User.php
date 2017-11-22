<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;

class User extends Authenticatable
{
    use Notifiable;

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
    //监听方法
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->activation_token = str_random(30);
        });
    }
    /**
    *头像
    */
    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }
    /**
    *重设密码
    */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
    /**
    *一个用户有多条微博
    */
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    /**
    *将当前用户发布过的所有微博从数据库中取出，并根据创建时间来倒序排序
    */
    public function feed()
    {
        return $this->statuses()
                    ->orderBy('created_at','desc');
    }
}
