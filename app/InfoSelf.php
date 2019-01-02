<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class InfoSelf extends Model
{
    use SoftDeletes; //使用软删除

    /**
     * The database table used by the model.
     * 定义模型对应数据表及主键
     * @var string
     */
    // protected $table = 'users';
    protected $table = 'zr_info_self';
    protected $primaryKey ='id';

    /**
     * The attributes that are mass assignable.
     * 定义可批量赋值字段
     * @var array
     */
    protected $fillable = ['code', 'name', 'user_telephone', 'return_record', 'old_bind', 'side_uim_number', 'side_uim_number_num', 'is_jituan', 'manage_name', 'manage_telephone', 'manage_id', 'project_name', 'new_telephone', 'uim_number', 'side_number', 'side_number_num','netin', 'collections', 'balance_month', 'collections_type', 'creater_id', 'status', 'package_month', 'remark', 'package_id'];

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

        if(!empty($queryList['status'])){
            switch ($queryList['status']) {
                case '3':
                    # 已返还全部金额
                    $query = $query->where('status', '3');
                    
                    break;
                case '2':
                    # 返还中...
                    $query = $query->where('status', '2');
                    
                    $query = $query->where('old_bind', '0');
                break;
                case '1':
                    # 尚未返还
                    $query = $query->where('status', '1');
                    
                    $query = $query->where('old_bind', '0');
                break;
                default:
                    // $query = $query->where('status', '1');
                    break;
            }
        }
         

        /*if(!empty($queryList['netin_year']) && !empty($queryList['netin_month'])){
            $netin = $queryList['netin_year'].'-'.$queryList['netin_month'];
            $query = $query->where('netin', $netin);
        }*/

        if(!empty($queryList['netin'])){
            $query = $query->where('netin', $queryList['netin']);
        }

        if(!empty($queryList['selectTelephone'])){
            $query = $query->where('new_telephone','like','%'.$queryList['selectTelephone'].'%');
        }

        if(isset($queryList['unPayed']) && ($queryList['unPayed'] == '1')){
            $query = $query->whereIn('status', ['1','2']);
            $query = $query->where('status','!=', '0');
            $query = $query->where('old_bind', '0');
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

    // 定义User表与infoSelf表一对多关系
    public function belongsToCreater(){

      // return $this->belongsTo('App\User', 'creater_id', 'id')->select('id as user_id', 'nick_name', 'telephone as creater_telephone');
      return $this->belongsTo('App\User', 'creater_id', 'id')->select('id', 'nick_name', 'telephone');
    }

    // 定义信息表与套餐表一对一关系
    public function hasOnePackage()
    {
        return $this->hasOne('App\Package', 'id', 'package_id');
    }

    // 定义信息表与套餐表一对一关系
    public function hasManyInfoDianxin()
    {
        return $this->hasMany('App\InfoDianxin', 'info_self_id', 'id' )->orderBy('balance_month');
    }
}
