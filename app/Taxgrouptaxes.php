<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxGroupTaxes extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'taxgrouptaxes';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'taxgroupid', 'taxauthid', 'calculationorder', 'taxontax'];

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

    // 定义TaxGroupTaxes表与TaxGroups表一对一关系
    public function belongsToTaxGroups()
    {
        return $this->belongsTo('App\TaxGroups', 'taxgroupid', 'taxgroupid');
    }

    // 定义TaxGroupTaxes表与TaxAuthorities表一对一关系
    public function belongsToTaxAuthorities()
    {
        return $this->belongsTo('App\TaxAuthorities', 'taxauthid', 'taxid');
    }

    // 定义User表与Chance表一对多关系
    public function hasManyChances()
    {

        return $this->hasMany('App\Chance', 'creater', 'id');
    }

}
