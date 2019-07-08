<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{

    use SoftDeletes; //使用软删除

    /**
     * The database table used by the model.
     * 定义模型对应数据表及主键
     * 淘车乐门店表
     * @var string
     */
    // protected $table = 'users';
    protected $table      = 'area';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     * 定义可批量赋值字段
     * @var array
     */
    protected $fillable = [];

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes excluded from the model's JSON form.
     * //在模型数组或 JSON 显示中隐藏某些属性
     * @var array
     */
    protected $hidden = [];

    // 定义Area表与User表一对多关系
    /*public function hasManyUser(){

    return $this->hasMany('App\User', 'creater_id', 'id');
    }*/

    // 定义Area表与Cars表一对多关系
    public function hasManyCars()
    {

        return $this->hasMany('App\Cars', 'plate_city', 'id');
    }

    // 定义Area表与Shop表一对多关系
    public function hasManyshops()
    {

        return $this->hasMany('App\Shop', 'city_id', 'id');
    }

    // 定义Area表与Insurance表一对多关系
    public function hasManyInsurancesByCity()
    {

        return $this->hasMany('App\Insurance', 'insurance_city', 'id');
    }

    // 定义Area表与Insurance表一对多关系
    public function hasManyInsurancesByProvence()
    {

        return $this->hasMany('App\Insurance', 'insurance_city', 'id');
    }

    // 定义Area表与Loans表一对多关系
    public function hasManyLoansByCity()
    {

        return $this->hasMany('App\Loan', 'insurance_city', 'id');
    }

    // 定义Area表与Loans表一对多关系
    public function hasManyLoansByProvence()
    {

        return $this->hasMany('App\Loan', 'insurance_city', 'id');
    }

}
