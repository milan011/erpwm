<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PcTab extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'pctabs';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'tabcode', 'usercode', 'typetabcode', 'currency', 'tablimit', 'assigner', 'authorizer', 'glaccountassignment', 'glaccountpcash'];

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

    // 定义PcTab表与PcTypeTab表一对一关系
    public function belongsToPcTypeTab()
    {
        return $this->belongsTo('App\PcTypeTab', 'typetabcode', 'id')->withDefault(['typetabdescription' => '',
        ]);
    }

    // 定义PcTab表与User表一对一关系
    public function belongsToUserWithAssign()
    {
        return $this->belongsTo('App\User', 'assigner', 'id')->withDefault(['realname' => '',
        ]);
    }

    // 定义PcTab表与User表一对一关系
    public function belongsToUserWithUscode()
    {
        return $this->belongsTo('App\User', 'usercode', 'id')->withDefault(['realname' => '',
        ]);
    }

    // 定义PcTab表与User表一对一关系
    public function belongsUserWithAuthorizer()
    {
        return $this->belongsTo('App\User', 'authorizer', 'id')->withDefault(['realname' => '',
        ]);
    }

    // 定义PcTab表与ChartMaster表一对一关系
    public function belongsToChartMasterWithAssignment()
    {
        return $this->belongsTo('App\ChartMaster', 'glaccountassignment', 'id')->withDefault(['accountname' => '',
        ]);
    }
    // 定义PcTab表与ChartMaster表一对一关系
    public function belongsToChartMasterWithCash()
    {
        return $this->belongsTo('App\ChartMaster', 'glaccountpcash', 'id')->withDefault(['accountname' => '',
        ]);
    }

    // 定义Example表与Notice表一对多关系
    public function hasManyNotice()
    {

        return $this->hasMany('App\Notice', 'user_id', 'id');
    }
}
