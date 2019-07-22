<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Taxauthrates extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'taxauthrates';
    protected $primaryKey = 'id';
    protected $fillable   = ['taxauthority', 'dispatchtaxprovince', 'taxcatid', 'taxrate'];

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

    // 定义Taxauthrates表与TaxGroupTaxes表一对一关系
    public function belongsToTaxGroupTaxes()
    {
        return $this->belongsTo('App\TaxGroupTaxes', 'taxauthid', 'taxid');
    }

    // 定义Taxauthrates表与Taxprovinces表一对一关系
    public function belongsToTaxprovinces()
    {
        return $this->belongsTo('App\Taxprovinces', 'dispatchtaxprovince', 'taxprovinceid');
    }

    // 定义Taxauthrates表与TaxGroupTaxes表一对一关系
    public function belongsToTaxCategories()
    {
        return $this->belongsTo('App\TaxCategories', 'taxcatid', 'taxcatid');
    }

    // 定义User表与Chance表一对多关系
    public function hasManyChances()
    {

        return $this->hasMany('App\Chance', 'creater', 'id');
    }

}
