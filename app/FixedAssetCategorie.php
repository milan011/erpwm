<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FixedAssetCategorie extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'fixedassetcategories';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'categorydescription', 'costact', 'depnact', 'disposalact', 'accumdepnact', 'defaultdepnrate', 'defaultdepntype', 'status'];

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

    // 搜索条件处理
    public function addCondition($queryList)
    {

        $query = $this;

        if (!empty($queryList['categorydescription'])) {
            $query = $query->where('categorydescription', 'like', '%' . $queryList['categorydescription'] . '%');
        }

        return $query;
    }

    // 定义FixedAssetCategorie表与ChartMaster表一对一关系
    public function belongsToChartMasterWithDepnact()
    {
        return $this->belongsTo('App\ChartMaster', 'depnact', 'id')->withDefault(['accountname' => '',
        ]);
    }

    // 定义FixedAssetCategorie表与ChartMaster表一对一关系
    public function belongsToChartMasterWithAccumdepnact()
    {
        return $this->belongsTo('App\ChartMaster', 'accumdepnact', 'id')->withDefault(['accountname' => '',
        ]);
    }

    // 定义FixedAssetCategorie表与ChartMaster表一对一关系
    public function belongsToChartMasterWithDisposalact()
    {
        return $this->belongsTo('App\ChartMaster', 'disposalact', 'id')->withDefault(['accountname' => '',
        ]);
    }

    // 定义FixedAssetCategorie表与ChartMaster表一对一关系
    public function belongsToChartMasterWithCostact()
    {
        return $this->belongsTo('App\ChartMaster', 'costact', 'id')->withDefault(['accountname' => '',
        ]);
    }

    // 定义FixedAssetCategorie表与FixedAssets表一对多关系
    public function hasManyFixedAssets()
    {
        return $this->hasMany('App\FixedAssets', 'assetcategoryid', 'id');
    }
}
