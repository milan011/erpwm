<?php
namespace App\Repositories\PaymentMethod;

use App\PaymentMethod;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\PaymentMethod\PaymentMethodRepositoryInterface;
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

class PaymentMethodRepository implements PaymentMethodRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['paymentid', 'paymentname', 'paymenttype', 'receipttype', 'usepreprintedstationery', 'opencashdrawer', 'status'];

    // 根据ID获得信息
    public function find($id)
    {
        return PaymentMethod::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new PaymentMethod(); // 返回的是一个Order实例,两种方法均可
        $query = $query->orderBy('paymentid', 'DESC');
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

            $paymentMethod = new PaymentMethod(); //税目

            $input = array_replace($requestData->all());
            $paymentMethod->fill($input);
            $paymentMethod = $paymentMethod->create($input);

            DB::commit();
            return $paymentMethod;

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
        $info = PaymentMethod::select($this->select_columns)->findorFail($id); //获取信息

        $info->paymentname             = $requestData->paymentname;
        $info->opencashdrawer          = $requestData->opencashdrawer;
        $info->paymenttype             = $requestData->paymenttype;
        $info->receipttype             = $requestData->receipttype;
        $info->usepreprintedstationery = $requestData->usepreprintedstationery;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info         = PaymentMethod::findorFail($id);
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
    public function isRepeat($paymentname)
    {
        return PaymentMethod::where('paymentname', $paymentname)->where('status', '1')->first();
    }
}
