<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'departments';
    protected $primaryKey = 'departmentid';
    protected $fillable   = ['departmentid', 'description', 'authoriser'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    /*protected $hidden = [
    'password', 'remember_token',
    ];*/

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    // 定义Department表与User表一对一关系
    public function belongsToUser()
    {
        return $this->belongsTo('App\User', 'authoriser', 'id')->withDefault(['realname' => '',
        ]);
    }

    // 定义Example表与Notice表一对多关系
    public function hasManyNotice()
    {

        return $this->hasMany('App\Notice', 'user_id', 'id');
    }
}
