<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'www_users';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'name', 'nick_name', 'password', 'telephone', 'phone', 'qq_number', 'wx_number', 'address', 'creater_id', 'shop_id', 'status', 'user_img', 'email'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    // 是否超级管理员
    public function isSuperAdmin()
    {

        // dd(Auth::user()->hasRole('admin'));
        return Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager');
        /*$user_role_id = Auth::user()->hasManyUserRole[0]->role_id; //用户角色id

    return ($user_role_id == config('zhuorui.user_role_type')['超级管理员']) || ($user_role_id == config('zhuorui.user_role_type')['管理员']);*/
    }

    // 是否店长
    public function isMdLeader()
    {

        $user_role_id = Auth::user()->hasManyUserRole[0]->role_id; //用户角色id
        // $user_role_id  = '6';
        return $user_role_id == config('tcl.user_role_type')['门店店长'];
    }

    // 是否贷款主管
    public function isDkLeader()
    {

        $user_role_id = Auth::user()->hasManyUserRole[0]->role_id; //用户角色id
        // $user_role_id  = '6';

        return ($user_role_id == config('tcl.user_role_type')['贷款主管']) || ($user_role_id == config('tcl.user_role_type')['保险/贷款主管']);
    }

    // 是否保险主管
    public function isBxLeader()
    {

        $user_role_id = Auth::user()->hasManyUserRole[0]->role_id; //用户角色id
        // $user_role_id  = '6';
        return ($user_role_id == config('tcl.user_role_type')['保险主管']) || ($user_role_id == config('tcl.user_role_type')['保险/贷款主管']);
    }

    public function tasksAssign()
    {
        return $this->hasMany('App\Tasks', 'fk_user_id_assign', 'id')
            ->where('status', 1)
            ->orderBy('deadline', 'asc');
    }
    public function tasksCreated()
    {
        return $this->hasMany('App\Tasks', 'fk_user_id_created', 'id')->limit(10);
    }

    public function tasksCompleted()
    {
        return $this->hasMany('App\Tasks', 'fk_user_id_assign', 'id')->where('status', 2);
    }

    public function tasksAll()
    {
        return $this->hasMany('App\Tasks', 'fk_user_id_assign', 'id')->whereIn('status', [1, 2]);
    }

    // 定义User表与role_user表一对多关系
    public function hasManyUserRole()
    {
        return $this->hasMany('App\RoleUser', 'user_id', 'id');
    }

    // 定义User表与role表多对多关系
    public function hasManyRoles()
    {
        return $this->belongsToMany('App\Role', 'role_user', 'user_id', 'role_id');
    }

    // 定义User表与Shop表一对一关系
    public function belongsToShop()
    {

        // return $this->hasOne('App\Shop', 'user_id', 'id')->select('user_id','name', 'address');
        return $this->belongsTo('App\Shop', 'shop_id', 'id');
    }

    // 定义User表与Notice表一对多关系
    public function hasManyNotice()
    {

        return $this->hasMany('App\Notice', 'user_id', 'id');
    }

    // 定义User表与Brand表一对多关系
    public function hasManyBrand()
    {

        return $this->hasMany('App\Brand', 'creater_id', 'id');
    }

    // 定义User表与Customer表一对多关系
    public function hasManyCustomer()
    {

        return $this->hasMany('App\Customer', 'creater_id', 'id');
    }

    // 定义User表与Cars表一对多关系
    public function hasManyCars()
    {

        return $this->hasMany('App\Cars', 'creater_id', 'id');
    }

    // 定义User表与Car_follow表一对多关系
    public function hasManyCarFollow()
    {

        return $this->hasMany('App\CarFollow', 'user_id', 'id');
    }

    // 定义User表与Want表一对多关系
    public function hasManyWants()
    {

        return $this->hasMany('App\Want', 'creater_id', 'id');
    }

    // 定义User表与want_follow表一对多关系
    public function hasManyWantFollow()
    {

        return $this->hasMany('App\WantFollow', 'user_id', 'id');
    }

    // 定义User表与Chance表一对多关系
    public function hasManyChances()
    {

        return $this->hasMany('App\Chance', 'creater', 'id');
    }

    // 定义User表与Chance表一对多关系(求购信息)
    public function hasManyChancesOnWant()
    {

        return $this->hasMany('App\Chance', 'want_customer_id', 'id');
    }

    // 定义User表与Chance表一对多关系(车源信息)
    public function hasManyChancesOnCar()
    {

        return $this->hasMany('App\Chance', 'car_customer_id', 'id');
    }

    // 定义User表与plan表一对多关系
    public function hasManyPlans()
    {

        return $this->hasMany('App\Plan', 'user_id', 'id');
    }

    // 定义User表与book表一对多关系
    public function hasManyTranscations()
    {
        return $this->hasMany('App\Transcation', 'user_id', 'id');
    }

    // 定义User表与Insurance表一对多关系
    public function hasManyLoans()
    {
        return $this->hasMany('App\Loan', 'user_id', 'id');
    }
}
