<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class PackageInfo extends Model
{
    /**
     * The database table used by the model.
     * 定义模型对应数据表及主键
     * @var string
     */
    // protected $table = 'users';
    protected $table = 'zr_package_info';
    protected $primaryKey ='id';

    /**
     * The attributes that are mass assignable.
     * 定义可批量赋值字段
     * @var array
     */
    protected $fillable = ['id', 'pid', 'return_price', 'nums', 'return_month', 'status', 'creater_id', 'created_at', 'updated_at'];

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
        DB::insert('INSERT IGNORE INTO '.$a->table.' ('.implode(',',array_keys($array)).
            ') values (?'.str_repeat(',?',count($array) - 1).')',array_values($array));
    }

    // 搜索条件处理
    public function addCondition($requestData){

        $query = $this;

        if(!empty($requestData['goods_status'])){

            $query = $query->where('goods_status', $requestData['goods_status']);
        }else{
                $query = $query->where('goods_status', '1');
        }       

        if(!empty($requestData['category_id'])){

            $query = $query->where('category_id', $requestData['category_id']);
        } 

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

    // 定义package表与packageInfo表一对多关系
    public function belongsToPackage(){

      return $this->belongsTo('App\Package', 'pid', 'id');
    }

}
