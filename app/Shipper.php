<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipper extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'shippers';
    protected $primaryKey = 'shipper_id';
    protected $fillable   = ['shipper_id', 'shippername', 'mincharge'];

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

    // 定义Shipper表与SalesOrders表一对一关系
    public function hasManySalesOrders()
    {

        // return $this->hasOne('App\Shop', 'user_id', 'id')->select('user_id','name', 'address');
        return $this->hasMany('App\SalesOrders', 'shipvia', 'shipper_id');
    }

    // 定义Shipper表与DebtorTrans表一对一关系
    public function hasManySalesDebtorTrans()
    {

        // return $this->hasOne('App\Shop', 'user_id', 'id')->select('user_id','name', 'address');
        return $this->hasMany('App\DebtorTrans', 'shipvia', 'shipper_id');
    }

}
