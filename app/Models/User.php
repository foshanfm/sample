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
    /**
    *一个用户（粉丝）能够关注多个人，而被关注者能够拥有多个粉丝
    *$user->followers()获取粉丝列表
    *$user->followings()获取关注列表
    */
    public function followers()
    {
        return $this->belongsToMany(User::Class, 'followers', 'user_id', 'follower_id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::Class,'followers', 'follower_id', 'user_id');
    }

    //关注他人
    public function follow($user_ids)
    {
        if(!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids,false);
    }
    //取消关注
    public function unfollow($user_ids)
    {
        if(!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }

    //是否关注了此人
    public function isFollowing($user_ids)
    {
        return $this->followings()->contains($user_ids);
    }
}
