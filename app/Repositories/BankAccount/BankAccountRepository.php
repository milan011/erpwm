<?php
namespace App\Repositories\BankAccount;

use App\BankAccount;
use App\Repositories\BankAccount\BankAccountRepositoryInterface;
use App\Repositories\BaseInterface\Repository;
use Auth;
use Datatables;
use DB;
use Debugbar;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PHPZen\LaravelRbac\Traits\Rbac;
use Planbon;
use Session;

class BankAccountRepository implements BankAccountRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id', 'accountcode', 'currcode', 'invoice', 'bankaccountcode', 'bankaccountname', 'bankaccountnumber', 'bankaddress'];

    // 根据ID获得信息
    public function find($id)
    {
        return BankAccount::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new BankAccount(); // 返回的是一个Order实例,两种方法均可
        // $query = $query->addCondition($queryList); //根据条件组合语句
        $query = $query->with('belongsToChartMaster')->where('status', '1')->orderBy('id', 'DESC');
        if (empty($queryList['page'])) {
            //无分页,全部返还
            return $query->get();
        } else {
            return $query->paginate(10);
        }
    }

    // 创建信息
    public function create($requestData)
    {

        DB::beginTransaction();
        try {

            $example = new BankAccount(); //银行账户

            $input = array_replace($requestData->all());
            $input = nullDel($input);
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
    public function update($requestData, $id)
    {
        // dd($requestData->all());
        $info = BankAccount::select($this->select_columns)->findorFail($id); //获取信息

        $info->accountcode       = $requestData->accountcode;
        $info->invoice           = $requestData->invoice;
        $info->bankaccountcode   = $requestData->bankaccountcode;
        $info->bankaccountname   = $requestData->bankaccountname;
        $info->bankaccountnumber = $requestData->bankaccountnumber;
        $info->bankaddress       = $requestData->bankaddress;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info                  = BankAccount::findorFail($id);
            $returnData['status']  = true;
            $returnData['message'] = '';

            if ($info->hasManyBankTrans->count() > 0) {
                $returnData['status']  = false;
                $returnData['message'] = '不能删除此银行账户，因为此账户已有交易';

                return $returnData;
            }

            $info->status = '0'; //删除银行账户
            $info->save();

            DB::commit();
            return $returnData;

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return false;
        }
    }

    //名称是否重复
    public function isRepeat($bankaccountname, $bankaccountcode, $accountcode)
    {
        return BankAccount::where('bankaccountname', $bankaccountname)
            ->where('bankaccountcode', $bankaccountcode)
            ->where('accountcode', $accountcode)
            ->where('status', '1')->first();
    }
}
