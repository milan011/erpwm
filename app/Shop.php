<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     * 定义模型对应数据表及主键
     * @var string
     */
    // protected $table = 'users';
    protected $table      = 'tcl_shop';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     * 定义可批量赋值字段
     * @var array
     */
    protected $fillable = ['id', 'name', 'brand', 'goods_from', 'type', 'in_price', 'goods_spec', 'goods_unit', 'is_food', 'status', 'creater_id', 'remark', 'created_at', 'updated_at'];

    /**
     * The attributes excluded from the model's JSON form.
     * //在模型数组或 JSON 显示中隐藏某些属性
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    // 插入数据时忽略唯一索引
    public static function insertIgnore($array)
    {
        $a = new static();
        if ($a->timestamps) {
            $now                 = \Carbon\Carbon::now();
            $array['created_at'] = $now;
            $array['updated_at'] = $now;
        }
        DB::insert('INSERT IGNORE INTO ' . $a->table . ' (' . implode(',', array_keys($array)) .
            ') values (?' . str_repeat(',?', count($array) - 1) . ')', array_values($array));
    }

    // 定义shop表与城市表一对多关系
    public function belongsToCity()
    {

        return $this->belongsTo('App\Area', 'city_id', 'id')->select('pid', 'id', 'name as city_name');
    }

    // 定义Shop表与Inventory表一对多关系
    public function hasManyInventory()
    {

        // return $this->hasMany('App\Inventory', 'goods_id', 'id')->where('status', '1');
        return $this->hasMany('App\Inventory', 'goods_id', 'id');
    }

    // 定义Shop表与InventoryDetail表一对多关系
    public function hasManyInventoryDetail()
    {

        // return $this->hasMany('App\Inventory', 'goods_id', 'id')->where('status', '1');
        return $this->hasMany('App\InventoryDetail', 'goods_id', 'id');
    }

    // 定义User表与Shop表一对多关系
    public function belongsToCreater()
    {

        return $this->belongsTo('App\User', 'creater_id', 'id')->select('id', 'nick_name', 'telephone');
    }
}
