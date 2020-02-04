<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrders extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'salesorders';
    protected $primaryKey = 'orderno';
    protected $fillable   = ['orderno', 'debtorno', 'branchcode', 'customerref', 'buyername', 'comments', 'orddate', 'ordertype', 'shipvia', 'deladd1', 'deladd2', 'deladd3', 'deladd4', 'deladd5', 'contactphone', 'contactemail', 'deladd6', 'deliverto', 'deliverblind', 'freightcost', 'fromstkloc', 'deliverydate', 'confirmeddate', 'printedpackingslip', 'datepackingslipprinted', 'quotation', 'quotedate', 'poplaced', 'salesperson'];

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

    // 定义Example表与Shop表一对一关系
    public function belongsToShop()
    {

        // return $this->hasOne('App\Shop', 'user_id', 'id')->select('user_id','name', 'address');
        return $this->belongsTo('App\Shop', 'shop_id', 'id')->withDefault(['accountname' => '',
        ]);
    }

    // 定义Example表与Notice表一对多关系
    public function hasManyNotice()
    {

        return $this->hasMany('App\Notice', 'user_id', 'id')->withDefault(['accountname' => '',
        ]);
    }
}
