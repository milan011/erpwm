<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FixedAssetLocation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'fixedassetlocations';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'locationid', 'locationdescription', 'parentlocationid'];

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

        if (!empty($queryList['locationdescription'])) {
            $query = $query->where('locationdescription', 'like', '%' . $queryList['locationdescription'] . '%');
        }

        return $query;
    }

    // 定义FixedAssetLocation表与FixedAssetLocation表一对一关系
    public function belongsToFixedAssetLocation()
    {
        return $this->belongsTo('App\FixedAssetLocation', 'parentlocationid', 'id')->withDefault(['locationdescription' => '无',
        ]);
    }

    // 定义FixedAssetLocation表与FixedAssetLocation表一对多关系
    public function hasManyFixedAssetLocation()
    {

        return $this->hasMany('App\FixedAssetLocation', 'parentlocationid', 'id');
    }

    // 定义FixedAssetLocation表与FixedAssets表一对多关系
    public function hasManyFixedAssets()
    {

        return $this->hasMany('App\FixedAssets', 'assetlocation', 'id');
    }
}
