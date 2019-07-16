<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Taxprovinces extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'taxprovinces';
    protected $primaryKey = 'taxprovinceid';
    protected $fillable   = ['taxprovinceid', 'taxprovincename'];

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
}
