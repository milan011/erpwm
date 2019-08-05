<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchOrders extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'purchorders';
    protected $primaryKey = 'orderno';
    protected $fillable   = ['orderno', 'supplierno', 'comments', 'orddate', 'rate', 'dateprinted', 'allowprint', 'initiator', 'requisitionno', 'intostocklocation', 'deladd1', 'deladd2', 'deladd3', 'deladd4', 'deladd5', 'deladd6', 'tel', 'suppdeladdress1', 'suppdeladdress2', 'suppdeladdress3', 'suppdeladdress4', 'suppdeladdress5', 'suppdeladdress6', 'suppliercontact', 'supptel', 'contact', 'version', 'revised', 'realorderno', 'deliveryby', 'deliverydate', 'status', 'stat_comment', 'paymentterms', 'port'];

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

    // 定义Example表与Shop表一对一关系
    public function belongsToShop()
    {
        return $this->belongsTo('App\Shop', 'shop_id', 'id')->withDefault(['accountname' => '',
        ]);
    }

    // 定义Example表与Notice表一对多关系
    public function hasManyNotice()
    {

        return $this->hasMany('App\Notice', 'user_id', 'id');
    }
}
