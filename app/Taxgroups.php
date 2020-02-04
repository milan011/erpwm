<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxGroups extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'taxgroups';
    protected $primaryKey = 'taxgroupid';
    protected $fillable   = ['taxgroupid', 'taxgroupdescription'];

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

    // 定义taxgroups表与Suppliers表一对多关系
    public function hasManySuppliers()
    {

        return $this->hasMany('App\Suppliers', 'taxgroupid', 'taxgroupid');
    }

    // 定义taxgroups表与Suppliers表一对多关系
    public function hasManyCustbranch()
    {

        return $this->hasMany('App\Custbranch', 'taxgroupid', 'taxgroupid');
    }

    public function hasManyTaxGroupTaxes()
    {
        // return $this->hasMany('App\TaxGroupTaxes', 'taxgroupid', 'taxgroupid')->with('belongsToTaxAuthorities');
        return $this->hasMany('App\TaxGroupTaxes', 'taxgroupid', 'taxgroupid');
    }

    public function hasManyTaxAuthorities()
    {
        return $this->hasManyThrough(
            'App\TaxAuthorities',
            'App\TaxGroupTaxes',
            'taxgroupid', // TaxGroupTaxes表使用的外键...
            'taxid', // TaxAuthorities表使用的外键...
            'taxgroupid', // TaxGroup表主键...
            'taxauthid' // TaxGroupTaxes表主键...
        );
    }

}
