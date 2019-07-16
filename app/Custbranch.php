<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Custbranch extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'custbranch';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'branchcode', 'debtorno', 'brname', 'braddress1', 'braddress2', 'braddress3', 'braddress4', 'braddress5', 'braddress6', 'lat', 'lng', 'estdeliverydays', 'area', 'salesman', 'fwddate', 'phoneno', 'faxno', 'contactname', 'email', 'defaultlocation', 'taxgroupid', 'defaultshipvia', 'deliverblind', 'disabletrans', 'brpostaddr1', 'brpostaddr2', 'brpostaddr3', 'brpostaddr4', 'brpostaddr5', 'brpostaddr6', 'custbranchcode', 'specialinstructions'];

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

    // 定义Custbrance表与TaxGroup表一对一关系
    public function belongsToTaxGroups()
    {
        return $this->belongsTo('App\TaxGroups', 'taxgroupid', 'taxgroupid');
    }

    // 定义User表与Notice表一对多关系
    /*public function hasManyNotice()
{

return $this->hasMany('App\Notice', 'user_id', 'id');
}*/
}
