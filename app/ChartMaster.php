<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChartMaster extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'chartmaster';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'accountcode', 'accountname', 'group_'];

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

    // 定义chartmaster表与Bankaccount表一对一关系
    public function belongsToBankAccount()
    {
        return $this->belongsTo('App\BankAccount', 'id', 'accountcode')->withDefault(['accountcode' => '',
        ]);
    }

    // 定义chartmaster表与Shop表一对一关系
    public function belongsToAccountGroup()
    {
        return $this->belongsTo('App\AccountGroup', 'group_', 'id')
            ->withDefault([
                'groupname' => '',
                'pandl'     => '',
            ]);
    }

    // 定义chartmaster表与ChartDetail表一对多关系
    public function hasManyChartDetail()
    {
        return $this->hasMany('App\ChartDetail', 'accountcode', 'id')->where('actual', '!=', '0');
    }

    // 定义chartmaster表与Gltrans表一对多关系
    public function hasManyGltrans()
    {
        return $this->hasMany('App\Gltrans', 'account', 'id');
    }

}
