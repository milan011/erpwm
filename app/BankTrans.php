<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankTrans extends Model {
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $guard_name = 'api'; // 使用任何你想要的守卫
	protected $table = 'banktrans';
	protected $primaryKey = 'banktransid';
	protected $fillable = ['banktransid', 'type', 'transno', 'bankact', 'ref', 'amountcleared', 'exrate', 'functionalexrate', 'transdate', 'banktranstype', 'amount', 'currcode', 'status'];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	/*protected $hidden = [
		    'password', 'remember_token',
	*/

	/**
	 * 表明模型是否应该被打上时间戳
	 *
	 * @var bool
	 */
	public $timestamps = false;

	// 定义BankTrans表与BankAccount表一对一关系
	public function belongsToBankAccount() {
		return $this->belongsTo('App\BankAccount', 'bankact_id', 'id')->withDefault(['bankaccountcode' => '',
		]);
	}

	// 定义Example表与Notice表一对多关系
	public function hasManyNotice() {

		return $this->hasMany('App\Notice', 'user_id', 'id');
	}
}
