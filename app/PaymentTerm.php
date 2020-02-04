<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTerm extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'paymentterms';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'termsindicator', 'terms', 'daysbeforedue', 'dayinfollowingmonth', 'paymenttype'];

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

    // 定义PaymentTerm表与Shop表一对一关系
    public function belongsToShop()
    {

        // return $this->hasOne('App\Shop', 'user_id', 'id')->select('user_id','name', 'address');
        return $this->belongsTo('App\Shop', 'shop_id', 'id')->withDefault(['accountname' => '',
        ]);
    }

    // 定义PaymentTerm表与Suppliers表一对多关系
    public function hasManySuppliers()
    {

        return $this->hasMany('App\Suppliers', 'paymentterms', 'id');
    }

    // 定义PaymentTerm表与DebtorsMaster表一对多关系
    public function hasManyDebtorsMaster()
    {

        return $this->hasMany('App\DebtorsMaster', 'paymentterms', 'id');
    }
}
