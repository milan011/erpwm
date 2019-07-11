<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Taxgroups extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'taxgroups';
    protected $primaryKey = 'taxgroupid';
    protected $fillable   = ['taxgroupid', 'taxgroupdescription'];

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

    // 定义User表与Shop表一对一关系
    public function belongsToShop()
    {

        // return $this->hasOne('App\Shop', 'user_id', 'id')->select('user_id','name', 'address');
        return $this->belongsTo('App\Shop', 'shop_id', 'id');
    }

    // 定义User表与Chance表一对多关系
    public function hasManyChances()
    {

        return $this->hasMany('App\Chance', 'creater', 'id');
    }

}
