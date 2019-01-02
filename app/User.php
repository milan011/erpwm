<?php

namespace App;

use Auth;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'zr_users';
    protected $primaryKey ='id';
    protected $fillable = ['id', 'name', 'nick_name', 'password', 'telephone', 'wx_number', 'address', 'creater_id', 'status', 'created_at',  'remark'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // 是否超级管理员
    public function isSuperAdmin() {

        // dd(Auth::user()->hasRole('admin'));
        return Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager');
        /*$user_role_id = Auth::user()->hasManyUserRole[0]->role_id; //用户角色id

        return ($user_role_id == config('zhuorui.user_role_type')['超级管理员']) || ($user_role_id == config('zhuorui.user_role_type')['管理员']);*/
    }

    // 定义User表与Package表一对多关系
    public function hasManyCreater() {

        return $this->hasMany('App\Package', 'creater_id', 'id');
    }

    // 定义User表与InfoSelf表一对多关系
    public function hasManyCreaterInfoSelf() {

        return $this->hasMany('App\InfoSelf', 'creater_id', 'id');
    }

    // 定义User表与Manager表一对多关系
    public function hasManyCreaterManager() {

        return $this->hasMany('App\Manager', 'creater_id', 'id');
    }

    // 定义User表与Goods表一对多关系
    public function hasManyCreaterGoods() {

        return $this->hasMany('App\Goods', 'creater_id', 'id');
    }

    // 定义User表与Service表一对多关系
    public function hasManyCreaterService() {

        return $this->hasMany('App\Service', 'creater_id', 'id');
    }

    // 定义User表与ServiceDetail表一对多关系
    public function hasManyCreaterServiceDetail() {

        return $this->hasMany('App\ServiceDetail', 'creater_id', 'id');
    }
}
