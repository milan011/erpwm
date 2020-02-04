<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notice extends Model
{
    use SoftDeletes; //使用软删除

    /**
     * The database table used by the model.
     * 定义模型对应数据表及主键
     * 淘车乐公告表
     * @var string
     */
    // protected $table = 'users';
    protected $table      = 'tcl_notice';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     * 定义可批量赋值字段
     * @var array
     */
    protected $fillable = ['title', 'content', 'type', 'user_id', 'sort', 'is_top'];

    /**
     * The attributes excluded from the model's JSON form.
     * //在模型数组或 JSON 显示中隐藏某些属性
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    // 定义User表与Notice表一对多关系
    public function belongsToUser()
    {

        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
