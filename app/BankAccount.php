<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'bankaccounts';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'accountcode', 'currcode', 'invoice', 'bankaccountcode', 'bankaccountname', 'bankaccountnumber', 'bankaddress'];

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

    // 定义BankAccount表与ChartMaster表一对一关系
    public function belongsToChartMaster()
    {
        return $this->belongsTo('App\ChartMaster', 'accountcode', 'id')->withDefault(['accountname' => '',
        ]);
    }

    // 定义BankAccount表与BankTrans表一对多关系
    public function hasManyBankTrans()
    {

        return $this->hasMany('App\BankTrans', 'bankact', 'id');
    }
}
