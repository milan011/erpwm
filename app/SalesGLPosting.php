<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesGLPosting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'salesglpostings';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'area', 'stkcat', 'discountglcode', 'salesglcode', 'salestype', 'status'];

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

    // 定义SalesGLPosting表与Area表一对一关系
    public function belongsToArea()
    {

        // return $this->hasOne('App\Shop', 'user_id', 'id')->select('user_id','name', 'address');
        return $this->belongsTo('App\Area', 'area', 'id')->withDefault(['areadescription' => '',
        ]);
    }

    // 定义SalesGLPosting表与StockCategory表一对一关系
    public function belongsToStockCategory()
    {

        // return $this->hasOne('App\Shop', 'user_id', 'id')->select('user_id','name', 'address');
        return $this->belongsTo('App\StockCategory', 'stkcat', 'id')->withDefault(['categorydescription' => '',
        ]);
    }
    // 定义SalesGLPosting表与ChartMaster表一对一关系
    public function belongsToChartMasterWithSalesglCode()
    {

        // return $this->hasOne('App\Shop', 'user_id', 'id')->select('user_id','name', 'address');
        return $this->belongsTo('App\ChartMaster', 'salesglcode', 'id')->withDefault(['accountname' => '',
        ]);
    }
    // 定义SalesGLPosting表与ChartMaster表一对一关系
    public function belongsToChartMasterWithDiscountglCode()
    {

        // return $this->hasOne('App\Shop', 'user_id', 'id')->select('user_id','name', 'address');
        return $this->belongsTo('App\ChartMaster', 'discountglcode', 'id')->withDefault(['accountname' => '',
        ]);
    }
    // 定义SalesGLPosting表与SaleType表一对一关系
    public function belongsToSaleType()
    {

        // return $this->hasOne('App\Shop', 'user_id', 'id')->select('user_id','name', 'address');
        return $this->belongsTo('App\SaleType', 'salestype', 'id')->withDefault(['salestype' => '',
        ]);
    }

    // 定义Example表与Notice表一对多关系
    public function hasManyNotice()
    {

        return $this->hasMany('App\Notice', 'user_id', 'id')->withDefault(['accountname' => '',
        ]);
    }
}
