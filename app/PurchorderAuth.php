<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchorderAuth extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'purchorderauth';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'userid', 'uid', 'currabrev', 'cancreate', 'authlevel', 'offhold'];

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

    // 定义PurchorderAuth表与user表一对一关系
    public function belongsToUser()
    {
        return $this->belongsTo('App\User', 'userid', 'id')->withDefault(['realname' => '']);
    }

    // 定义PurchorderAuth表与Currencies表一对一关系
    public function belongsToCurrencies()
    {
        return $this->belongsTo('App\Currencies', 'currabrev', 'currabrev')->withDefault(['currabrev' => '',
        ]);
    }

    // 定义Example表与Notice表一对多关系
    public function hasManyNotice()
    {

        return $this->hasMany('App\Notice', 'user_id', 'id')->withDefault(['accountname' => '',
        ]);
    }
}
