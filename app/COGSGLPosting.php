<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class COGSGLPosting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'cogsglpostings';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'area', 'stkcat', 'glcode', 'salestype'];

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
    public function belongsToChartMaster()
    {

        // return $this->hasOne('App\Shop', 'user_id', 'id')->select('user_id','name', 'address');
        return $this->belongsTo('App\ChartMaster', 'glcode', 'id')->withDefault(['accountname' => '',
        ]);
    }
    // 定义SalesGLPosting表与SaleType表一对一关系
    public function belongsToSaleType()
    {

        // return $this->hasOne('App\Shop', 'user_id', 'id')->select('user_id','name', 'address');
        return $this->belongsTo('App\SaleType', 'salestype', 'id')->withDefault(['salestype' => '',
        ]);
    }
}
