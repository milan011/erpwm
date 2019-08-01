<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'stockcategory';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'categoryid', 'categorydescription', 'stocktype', 'stockact', 'adjglact', 'issueglact', 'purchpricevaract', 'materialuseagevarac', 'wipact', 'defaulttaxcatid'];

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

    // 定义StockCategory表与TaxCategories表一对一关系
    public function belongsToTaxCategories()
    {
        return $this->belongsTo('App\TaxCategories', 'defaulttaxcatid', 'taxcatid')->withDefault(['taxcatname' => '',
        ]);
    }

    // 定义StockCategory表与ChartMaster表一对一关系
    public function belongsToChartMasterWithStockact()
    {
        return $this->belongsTo('App\ChartMaster', 'stockact', 'id')->withDefault(['accountname' => '',
        ]);
    }
    // 定义StockCategory表与ChartMaster表一对一关系
    public function belongsToChartMasterWithWipact()
    {
        return $this->belongsTo('App\ChartMaster', 'wipact', 'id')->withDefault(['accountname' => '',
        ]);
    }

    // 定义StockCategory表与ChartMaster表一对一关系
    public function belongsToChartMasterWithAdjglact()
    {
        return $this->belongsTo('App\ChartMaster', 'adjglact', 'id')->withDefault(['accountname' => '',
        ]);
    }

    // 定义StockCategory表与ChartMaster表一对一关系
    public function belongsToChartMasterWithIssueglact()
    {
        return $this->belongsTo('App\ChartMaster', 'issueglact', 'id')->withDefault(['accountname' => '',
        ]);
    }

    // 定义StockCategory表与ChartMaster表一对一关系
    public function belongsToChartMasterWithPurchpricevaract()
    {
        return $this->belongsTo('App\ChartMaster', 'purchpricevaract', 'id')->withDefault(['accountname' => '',
        ]);
    }

    // 定义StockCategory表与ChartMaster表一对一关系
    public function belongsToChartMasterWithMaterialuseagevarac()
    {
        return $this->belongsTo('App\ChartMaster', 'materialuseagevarac', 'id')->withDefault(['accountname' => '',
        ]);
    }

    // 定义StockCategory表与StockMaster表一对多关系
    public function hasManyStockMaster()
    {
        return $this->hasMany('App\StockMaster', 'categoryid', 'id');
    }

    // 定义StockCategory表与StockCatProperties表一对多关系
    public function hasManyStockCatProperties()
    {
        return $this->hasMany('App\StockCatProperties', 'categoryid', 'id');
    }
}
