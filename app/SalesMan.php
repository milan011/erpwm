<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesMan extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'salesman';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'salesmanname', 'smantel', 'smanfax', 'commissionrate1', 'breakpoint', 'commissionrate2', 'current', 'status'];

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

    // 定义SalesMan表与User表一对一关系
    public function hasManyUser()
    {

        // return $this->hasOne('App\Shop', 'user_id', 'id')->select('user_id','name', 'address');
        return $this->hasMany('App\User', 'salesman', 'id');
    }

    // 定义SalesMan表与Custbranch表一对一关系
    public function hasManyCustbranch()
    {

        // return $this->hasOne('App\Shop', 'user_id', 'id')->select('user_id','name', 'address');
        return $this->hasMany('App\Custbranch', 'salesman', 'id');
    }

    // 定义SalesMan表与SalesAnalysis表一对一关系
    public function hasManySalesAnalysis()
    {

        // return $this->hasOne('App\Shop', 'user_id', 'id')->select('user_id','name', 'address');
        return $this->hasMany('App\SalesAnalysis', 'salesperson', 'id');
    }

    // 定义Example表与Notice表一对多关系
    public function hasManyNotice()
    {

        return $this->hasMany('App\Notice', 'user_id', 'id')->withDefault(['accountname' => '',
        ]);
    }
}
