<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locations extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'locations';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'locationname', 'deladd1', 'deladd2', 'deladd3', 'deladd4', 'deladd5', 'deladd6', 'tel', 'fax', 'email', 'contact', 'taxprovinceid', 'cashsalecustomer', 'cashsalebranch', 'managed', 'internalrequest', 'status'];

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

    // 定义Locations表与Taxprovinces表一对一关系
    public function belongsToTaxprovinces()
    {
        return $this->belongsTo('App\Taxprovinces', 'taxprovinceid', 'taxprovinceid')->withDefault(['taxprovincename' => '',
        ]);
    }

    // 定义Locations表与DebtorsMaster表一对一关系
    public function belongsToDebtorsMaster()
    {
        return $this->belongsTo('App\DebtorsMaster', 'cashsalecustomer', 'id')->withDefault(['name' => '',
        ]);
    }

    // 定义Locations表与Custbranch表一对一关系
    public function belongsToCustbranch()
    {
        return $this->belongsTo('App\Custbranch', 'cashsalebranch', 'id')->withDefault(['brname' => '',
        ]);
    }

    // 定义Locations表与Locstock表一对多关系
    public function hasManyLocstock()
    {

        return $this->hasMany('App\Locstock', 'loccode', 'id');
    }

    // 定义Locations表与SalesOrders表一对多关系
    public function hasManySalesOrders()
    {

        return $this->hasMany('App\SalesOrders', 'fromstkloc', 'id');
    }

    // 定义Locations表与StockMoves表一对多关系
    public function hasManyStockMoves()
    {

        return $this->hasMany('App\StockMoves', 'loccode', 'id');
    }

    // 定义Locations表与Locstock表一对多关系
    public function hasManyLocstockHasQuantity()
    {

        return $this->hasMany('App\Locstock', 'loccode', 'id')->where('quantity', '!=', '0');
    }

    // 定义Locations表与User表一对多关系
    public function hasManyUser()
    {

        return $this->hasMany('App\User', 'defaultlocation', 'id');
    }

    // 定义Locations表与Bom表一对多关系
    public function hasManyBom()
    {

        return $this->hasMany('App\Bom', 'loccode', 'id');
    }

    // 定义Locations表与WorkCentres表一对多关系
    public function hasManyWorkCentres()
    {

        return $this->hasMany('App\WorkCentres', 'location', 'id');
    }

    // 定义Locations表与WorkOrders表一对多关系
    public function hasManyWorkOrders()
    {

        return $this->hasMany('App\WorkOrders', 'loccode', 'id');
    }

    // 定义Locations表与Custbranch表一对多关系
    public function hasManyCustbranch()
    {

        return $this->hasMany('App\Custbranch', 'defaultlocation', 'id');
    }

    // 定义Locations表与PurchOrders表一对多关系
    public function hasManyPurchOrders()
    {

        return $this->hasMany('App\PurchOrders', 'intostocklocation', 'id');
    }

}
