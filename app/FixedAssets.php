<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FixedAssets extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'fixedassets';
    protected $primaryKey = 'assetid';
    protected $fillable   = ['assetid', 'serialno', 'barcode', 'assetlocation', 'cost', 'accumdepn', 'datepurchased', 'disposalproceeds', 'assetcategoryid', 'description', 'longdescription', 'depntype', 'depnrate', 'disposaldate', 'status'];

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

        if (!empty($queryList['description'])) {
            $query = $query->where('description', 'like', '%' . $queryList['description'] . '%');
        }

        return $query;
    }

    // 定义FixedAssets表与FixedAssetLocation表一对一关系
    public function belongsToFixedAssetLocation()
    {
        return $this->belongsTo('App\FixedAssetLocation', 'assetlocation', 'id')->withDefault(['locationdescription' => '',
        ]);
    }

    // 定义FixedAssets表与FixedAssetCategorie表一对一关系
    public function belongsToFixedAssetCategorie()
    {
        return $this->belongsTo('App\FixedAssetCategorie', 'assetcategoryid', 'id')->withDefault(['categorydescription' => '',
        ]);
    }

    // 定义Example表与Notice表一对多关系
    public function hasManyNotice()
    {

        return $this->hasMany('App\Notice', 'user_id', 'id');
    }
}
