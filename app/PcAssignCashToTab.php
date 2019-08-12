<?php

namespace App;

use Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PcAssignCashToTab extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'pcashdetails';
    protected $primaryKey = 'counterindex';
    protected $fillable   = ['counterindex', 'tabcode', 'date', 'codeexpense', 'amount', 'authorized', 'posted', 'notes', 'receipt', 'status'];

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
    }

    // 定义PcExpensesTypeTab表与PcTab表一对一关系
    public function belongsToPcTab()
    {
        return $this->belongsTo('App\PcTab', 'tabcode', 'id')
        // ->where('assigner', '5')
            ->withDefault(['tabcode' => '']);
    }

    // 定义PcExpensesTypeTab表与PcExpenses表一对一关系
    public function belongsToPcExpenses()
    {
        return $this->belongsTo('App\PcExpenses', 'codeexpense', 'id')
        // ->where('assigner', '5')
            ->withDefault(['description' => '']);
    }
}
