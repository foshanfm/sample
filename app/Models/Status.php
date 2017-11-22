<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    //我们未在微博模型中定义 fillable 属性，来指定在微博模型中可以进行正常更新的字段，Laravel 在尝试保护
    protected $fillable = ['content'];
    //一条微博属于一个用户，一对多的反向关联
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
