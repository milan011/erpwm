<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suppliers extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'suppliers';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'supplierid', 'suppname', 'address1', 'address2', 'address3', 'address4', 'address5', 'address6', 'supptype', 'lat', 'lng', 'currcode', 'suppliersince', 'paymentterms', 'lastpaid', 'lastpaiddate', 'bankact', 'bankref', 'bankpartics', 'remittance', 'taxgroupid', 'factorcompanyid', 'taxref', 'phn', 'port', 'email', 'fax', 'telephone', 'url'];

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

    // 定义Suppliers表与TaxGroup表一对一关系
    public function belongsToTaxGroups()
    {
        return $this->belongsTo('App\TaxGroups', 'taxgroupid', 'taxgroupid');
    }
}
