<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use DB;

class ServiceDetail extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     * 定义模型对应数据表及主键
     * @var string
     */
    // protected $table = 'users';
    protected $table = 'zr_service_detail';
    protected $primaryKey ='id';

    /**
     * The attributes that are mass assignable.
     * 定义可批量赋值字段
     * @var array
     */
    protected $fillable = ['id', 'name', 'customer', 'service_id', 'charge_price', 'inventory_percentage','inventory_profit', 'goods_cost', 'inventer_ticheng', 'status', 'goods_num', 'customer_telephone', 'remark', 'creater_id', 'created_at', 'updated_at'];

    /**
     * The attributes excluded from the model's JSON form.
     * //在模型数组或 JSON 显示中隐藏某些属性
     * @var array
     */
    protected $hidden = [   
        
    ];

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
        DB::insert('INSERT IGNORE INTO '.$a->table.' ('.implode(',',array_keys($array)).
            ') values (?'.str_repeat(',?',count($array) - 1).')',array_values($array));
    }

    // 搜索条件处理
    public function addCondition($queryList){

        $query = $this;

        // dd($queryList);

        if(!(Auth::user()->isSuperAdmin())){

            $query = $query->where('creater_id', Auth::id());  
        }else{
            if(!empty($queryList['creater_id'])){
                $query = $query->where('creater_id',$queryList['creater_id']);
            }
        }         

        if(!empty($queryList['service_id'])){
            $query = $query->where('service_id',$queryList['service_id']);
        }

        if(!empty($queryList['goods_id'])){
            $query = $query->where('goods_id', $queryList['goods_id']);
        }

        if(!empty($queryList['selectDate'])){
            $query = $query->whereBetween('created_at', $queryList['selectDate']);
        }
       
        return $query;
    }

    // 定义套餐表与信息表一对多关系
    public function belongsToInfoSelf(){

      return $this->belongsTo('App\InfoSelf', 'id', 'package_id');
    }

    // 定义ServiceDetail表与ServiceDetailGoods表一对多关系
    public function hasManyServiceDetailGoods(){

      return $this->hasMany('App\ServiceDetailGoods', 'service_detail_id', 'id');
    }

    // 定义ServiceDetail表与InventoryDetail表一对多关系
    public function hasManyInventoryDetail(){
        return $this->hasMany('App\InventoryDetail', 'service_detail_id', 'id');
    }

    // 定义User表与ServiceDetail表一对多关系
    public function belongsToCreater(){

      return $this->belongsTo('App\User', 'creater_id', 'id')->select('id', 'nick_name', 'telephone');
    }

    // 定义Service表与ServiceDetail表一对多关系
    public function belongsToService(){

      return $this->belongsTo('App\Service', 'service_id', 'id')->select('id', 'name');
    }
}
