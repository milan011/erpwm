<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountGroup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'accountgroups';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'groupname', 'pid', 'sectioninaccounts', 'pandl', 'sequenceintb', 'parentgroupname', 'status'];

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

    // 定义AccountGroup表与AccountSection表一对一关系
    public function belongsToAccountSection()
    {
        return $this->belongsTo('App\AccountSection', 'sectioninaccounts', 'sectionid')->withDefault(['sectionname' => '',
        ]);
    }

    // 定义AccountGroup表与AccountGroup表一对一关系
    public function belongsToAccountGroup()
    {
        return $this->belongsTo('App\AccountGroup', 'pid', 'id')->withDefault(['groupname' => '顶层组',
        ]);
    }

    // 定义AccountGroup表与ChartMaster表一对多关系
    public function hasManyChartMasters()
    {

        return $this->hasMany('App\ChartMaster', 'group_', 'id');
    }
}
