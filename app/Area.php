<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'areas';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'areadescription'];

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

    // 定义SalesMan表与Custbranch表一对一关系
    public function hasManyCustbranch()
    {

        // return $this->hasOne('App\Shop', 'user_id', 'id')->select('user_id','name', 'address');
        return $this->hasMany('App\Custbranch', 'area', 'id');
    }

    // 定义SalesMan表与SalesAnalysis表一对一关系
    public function hasManySalesAnalysis()
    {

        // return $this->hasOne('App\Shop', 'user_id', 'id')->select('user_id','name', 'address');
        return $this->hasMany('App\SalesAnalysis', 'area', 'id');
    }
}
