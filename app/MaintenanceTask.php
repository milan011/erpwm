<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceTask extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'fixedassettasks';
    protected $primaryKey = 'taskid';
    protected $fillable   = ['taskid', 'assetid', 'taskdescription', 'frequencydays', 'lastcompleted', 'userresponsible', 'manager', 'status'];

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
    /*public function addCondition($queryList)
    {

    $query = $this;

    if (!empty($queryList['tabcode'])) {
    $query = $query->where('tabcode', $queryList['tabcode']);
    }
    // dd($queryList['posted']);
    if (!empty($queryList['posted'])) {
    $query = $query->where('posted', $queryList['posted']);
    }
    if (!empty($queryList['codeexpense'])) {
    $query = $query->where('codeexpense', $queryList['codeexpense']);
    }
    // $query = $query->where('posted', $queryList['posted']);
    if (!empty($queryList['date'])) {
    // dd($queryList['date']);
    $start = substr($queryList['date'][0], 0, 10);
    $end   = substr($queryList['date'][1], 0, 10);

    $query = $query->whereBetween('date', [$start, $end]);
    }

    return $query;
    }*/

    // 定义MaintenanceTask表与FixedAssetItem表一对一关系
    public function belongsToFixedAssetItem()
    {
        return $this->belongsTo('App\FixedAssetItem', 'assetid', 'assetid')->withDefault(['description' => '',
        ]);
    }

    // 定义MaintenanceTask表与User表一对一关系
    public function belongsToUserWithUserresponsible()
    {
        return $this->belongsTo('App\User', 'userresponsible', 'id')->withDefault(['description' => '',
        ]);
    }

    // 定义MaintenanceTask表与User表一对一关系
    public function belongsToUserWithManager()
    {
        return $this->belongsTo('App\User', 'manager', 'id')->withDefault(['description' => '',
        ]);
    }

    // 定义Example表与Notice表一对多关系
    public function hasManyNotice()
    {

        return $this->hasMany('App\Notice', 'user_id', 'id');
    }
}
