<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FreightCost extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard_name = 'api'; // 使用任何你想要的守卫
    protected $table      = 'freightcosts';
    protected $primaryKey = 'shipcostfromid';
    protected $fillable   = ['shipcostfromid', 'locationfrom', 'destination', 'shipperid', 'cubrate', 'kgrate', 'maxkgs', 'maxcub', 'fixedprice', 'minimumchg'];

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

    // 定义FreightCost表与Shipper表一对一关系
    public function belongsToShipper()
    {
        return $this->belongsTo('App\Shipper', 'shipperid', 'shipper_id')->withDefault(['shippername' => '',
        ]);
    }

    // 定义FreightCost表与Locations表一对一关系
    public function belongsToLocations()
    {
        return $this->belongsTo('App\Locations', 'locationfrom', 'id')->withDefault(['locationname' => '',
        ]);
    }

    // 定义Example表与Notice表一对多关系
    public function hasManyNotice()
    {

        return $this->hasMany('App\Notice', 'user_id', 'id')->withDefault(['accountname' => '',
        ]);
    }
}
