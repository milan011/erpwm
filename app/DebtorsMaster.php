<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DebtorsMaster extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'debtorsmaster';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'debtorno', 'name', 'address1', 'address2', 'address3', 'address4', 'address5', 'address6', 'salestype', 'currcode', 'clientsince', 'paymentterms', 'holdreason', 'discount', 'pymtdiscount', 'lastpaid', 'lastpaiddate', 'creditlimit', 'invaddrbranch', 'discountcode', 'ediinvoices', 'ediorders', 'edireference', 'editransport', 'ediaddress', 'ediserveruser', 'ediserverpwd', 'taxref', 'customerpoline', 'typeid', 'language_id'];

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

    // 定义DebtorsMaster表与Shop表一对一关系
    public function belongsToShop()
    {

        // return $this->hasOne('App\Shop', 'user_id', 'id')->select('user_id','name', 'address');
        return $this->belongsTo('App\Shop', 'shop_id', 'id')->withDefault(['accountname' => '',
        ]);
    }

    // 定义DebtorsMaster表与Notice表一对多关系
    public function hasManyNotice()
    {

        return $this->hasMany('App\Notice', 'user_id', 'id')->withDefault(['accountname' => '',
        ]);
    }
}
