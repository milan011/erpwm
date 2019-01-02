<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Auth;

class InfoDianxin extends Model
{

    use SoftDeletes; //使用软删除

    /**
     * The database table used by the model.
     * 定义模型对应数据表及主键
     * @var string
     */
    // protected $table = 'users';
    protected $table = 'zr_info_dianxin';
    protected $primaryKey ='id';

    /**
     * The attributes that are mass assignable.
     * 定义可批量赋值字段
     * @var array
     */
    protected $fillable = ['id','code', 'name', 'jiakuan', 'refunds', 'info_self_id', 'manager', 'jituan', 'balance_month', 'return_telephone','yongjin', 'netin', 'creater_id', 'status', 'remark'];

    /**
     * The attributes excluded from the model's JSON form.
     * //在模型数组或 JSON 显示中隐藏某些属性
     * @var array
     */
    protected $hidden = [];

    /**
     * 应该被调整为日期的属性
     * 定义软删除
     * @var array
     */
    protected $dates = ['deleted_at'];

    // 插入数据时忽略唯一索引
    public static function insertIgnore($array){
        $a = new static();
        if($a->timestamps){
            $now = \Carbon\Carbon::now();
            $array['created_at'] = $now;
            $array['updated_at'] = $now;
        }

        $id = DB::insert('INSERT IGNORE INTO '.$a->table.' ('.implode(',',array_keys($array)).
            ') values (?'.str_repeat(',?',count($array) - 1).')',array_values($array));

        // dd(lastSql());
        return $id;
    }

    // 搜索条件处理
    public function addCondition($queryList){

        $query = $this;  
        if(!empty($queryList['status'])){
            //有订单状态选项
            $query = $query->where('status', $queryList['status']);
        } 
        if(!empty($queryList['netin'])){
            $query = $query->where('netin', $queryList['netin']);
        }

        if(!empty($queryList['selectTelephone'])){
            $query = $query->where('return_telephone','like',$queryList['selectTelephone'].'%');
        }
        // dd();
        return $query;
    }

     /**
     * 推荐车型信息的查询作用域
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOsRecommend($query, $requestData)
    {
        if(isset($requestData['top_price'])){
            $query = $query->where('top_price', '<=', $requestData['top_price']);
        }
        
        if(isset($requestData['bottom_price'])){
            $query = $query->where('bottom_price', '>=', $requestData['bottom_price']);
        }
        
        $query = $query->where('car_status', '1');
        return $query;
    }

    // 定义User表与order表一对多关系
    public function belongsToCreater(){

      return $this->belongsTo('App\User', 'creater_id', 'id')->select('id', 'nick_name', 'telephone');
    }

    // 定义infoDianxin表与infoSelf表一对多关系
    public function belongsToInfoSelf(){

      return $this->belongsTo('App\InfoSelf', 'info_self_id', 'id');
    }

    // 定义goods表与order_goods表远程一对多关系
    public function hasManyGoods()
    {
        return $this->hasManyThrough('App\Goods', 'App\Category', 'id', 'category_id', 'category_id')
                    ->select('yz_goods.id as goods_id', 'yz_goods.name as goods_name');
    }

}
