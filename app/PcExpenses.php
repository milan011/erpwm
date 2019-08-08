<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PcExpenses extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'pcexpenses';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'description', 'glaccount', 'tag'];

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

    // 定义PcExpenses表与Tags表一对一关系
    public function belongsToTags()
    {
        return $this->belongsTo('App\Tags', 'tag', 'tagref')->withDefault(['tagdescription' => '',
        ]);
    }

    // 定义PcExpenses表与ChartMaster表一对一关系
    public function belongsToChartMaster()
    {
        return $this->belongsTo('App\ChartMaster', 'glaccount', 'id')->withDefault(['accountname' => '',
        ]);
    }
}
