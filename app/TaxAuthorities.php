<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxAuthorities extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'taxauthorities';
    protected $primaryKey = 'taxid';
    protected $fillable   = ['taxid', 'description', 'taxglcode', 'purchtaxglaccount', 'bank', 'bankacctype', 'bankacc', 'bankswift'];

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

    // 定义Taxauthrates表与TaxGroupTaxes表一对多关系
    public function hasManyTaxGroupTaxes()
    {
        return $this->hasMany('App\TaxGroupTaxes', 'taxauthid', 'taxid')->with('belongsToTaxGroups');
    }

    // 定义TaxAuthorities表与Taxauthrates表一对多关系
    public function hasManyTaxauthrates()
    {
        return $this->hasMany('App\Taxauthrates', 'taxauthority', 'taxid');
    }

    // 定义User表与Chance表一对多关系
    public function hasManyChances()
    {

        return $this->hasMany('App\Chance', 'creater', 'id');
    }

    // 定义TaxAuthorities表与ChartMaster表一对一关系
    public function belongsToChartMasterByTaxglcode()
    {
        return $this->belongsTo('App\ChartMaster', 'taxglcode', 'id')->withDefault(['accountname' => '',
        ]);
    }

    // 定义TaxAuthorities表与ChartMaster表一对一关系
    public function belongsToChartMasterByPurchtaxglaccount()
    {
        return $this->belongsTo('App\ChartMaster', 'purchtaxglaccount', 'id')->withDefault([
            'accountname' => '',
        ]);
    }
}
