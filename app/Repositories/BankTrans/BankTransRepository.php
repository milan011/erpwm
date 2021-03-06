<?php
namespace App\Repositories\BankTrans;

use App\BankAccountUsers;
use App\BankTrans;
use App\Repositories\BankTrans\BankTransRepositoryInterface;
use DB;

class BankTransRepository implements BankTransRepositoryInterface {
	//默认查询数据
	protected $select_columns = ['banktransid', 'type', 'transno', 'bankact', 'ref', 'amountcleared', 'exrate', 'functionalexrate', 'transdate', 'banktranstype', 'amount', 'currcode', 'status'];

	// 根据ID获得信息
	public function find($id) {
		return BankTrans::select($this->select_columns)
			->findOrFail($id);
	}

	// 获取用户授权银行列表
	public function getUserBankList($id) {
		// dd($id);
		return BankAccountUsers::select(['account_id', 'user_id', 'userid'])
			->with('hasOneBank')
			->where('user_id', $id)
			->get();
	}

	// 根据不同参数获得信息列表
	public function getList($queryList) {
		// dd($queryList);
		$query = new BankTrans(); // 返回的是一个Order实例,两种方法均可
		$query = $query->where('status', '1')->orderBy('banktransid', 'DESC');
		$query = $query->whereIn('bankact_id', $queryList['userBanks']);
		$query = $query->with('belongsToBankAccount');
		if (empty($queryList['page'])) {
			//无分页,全部返还
			return $query->get();
		} else {
			return $query->paginate(10);
		}
	}

	// 创建信息
	public function create($requestData) {

		DB::beginTransaction();
		try {

			$example = new BankTrans(); //税目

			$input = array_replace($requestData->all());
			$example->fill($input);
			$example = $example->create($input);

			DB::commit();
			return $example;

		} catch (\Exception $e) {
			throw $e;
			DB::rollBack();
			return false;
		}

	}

	// 信息更新
	public function update($requestData, $id) {
		// dd($requestData->all());
		$info = BankTrans::select($this->select_columns)->findorFail($id); //获取信息

		$info->taxcatname = $requestData->taxcatname;

		$info->save();

		return $info;
	}

	// 删除信息
	public function destroy($id) {
		DB::beginTransaction();
		try {
			$info = BankTrans::findorFail($id);
			$info->status = '0'; //删除税目
			$info->save();

			DB::commit();
			return $info;

		} catch (\Illuminate\Database\QueryException $e) {
			DB::rollBack();
			return false;
		}
	}

	//名称是否重复
	public function isRepeat($taxcatname) {
		return BankTrans::where('taxcatname', $taxcatname)->where('status', '1')->first();
	}
}
