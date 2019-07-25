<?php
namespace App\Repositories\PaymentTerm;

use App\PaymentTerm;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\PaymentTerm\PaymentTermRepositoryInterface;
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

class PaymentTermRepository implements PaymentTermRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id', 'termsindicator', 'terms', 'daysbeforedue', 'dayinfollowingmonth', 'status', 'paymenttype'];

    // 根据ID获得信息
    public function find($id)
    {
        return PaymentTerm::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new PaymentTerm(); // 返回的是一个Order实例,两种方法均可
        $query = $query->where('status', '1')->orderBy('id', 'DESC');
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

            $payment_term = new PaymentTerm(); //税目

            // dd($tax_provinces);
            // dd($tax_authorities->get());
            $input = array_replace($requestData->all());
            // dd($input);
            $payment_term->fill($input);
            $payment_term = $payment_term->create($input);

            DB::commit();
            return $payment_term;

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
        $info = PaymentTerm::select($this->select_columns)->findorFail($id); //获取信息

        $info->termsindicator      = $requestData->termsindicator;
        $info->terms               = $requestData->terms;
        $info->daysbeforedue       = $requestData->daysbeforedue;
        $info->dayinfollowingmonth = $requestData->dayinfollowingmonth;
        $info->paymenttype         = $requestData->paymenttype;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info         = PaymentTerm::findorFail($id);
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
    public function isRepeat($terms)
    {
        return PaymentTerm::where('terms', $terms)->where('status', '1')->first();
    }
}
