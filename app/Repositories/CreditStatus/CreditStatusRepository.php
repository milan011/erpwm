<?php
namespace App\Repositories\CreditStatus;

use App\CreditStatus;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\CreditStatus\CreditStatusRepositoryInterface;
use App\TaxGroupTaxes;
use Auth;
use Carbon;
use Datatables;
use DB;
use Debugbar;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PHPZen\LaravelRbac\Traits\Rbac;
use Planbon;
use Session;

class CreditStatusRepository implements CreditStatusRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id', 'reasondescription', 'dissallowinvoices', 'status'];

    // 根据ID获得信息
    public function find($id)
    {
        return CreditStatus::select($this->select_columns)->with('hasManyTaxGroupTaxes')
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new CreditStatus(); // 返回的是一个Order实例,两种方法均可
        $query = $query->where('status', '1')->orderBy('id', 'DESC');
        if ($queryList['withOutPage']) {
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

            $sale_type = new CreditStatus(); //税目

            // dd($tax_provinces);
            // dd($tax_authorities->get());
            $input = array_replace($requestData->all());
            $sale_type->fill($input);
            $sale_type = $sale_type->create($input);

            DB::commit();
            return $sale_type;

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
        $info = CreditStatus::select($this->select_columns)->findorFail($id); //获取信息

        $info->reasondescription = $requestData->reasondescription;
        $info->dissallowinvoices = $requestData->dissallowinvoices;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info         = CreditStatus::findorFail($id);
            $info->status = '0';
            $info->save(); //删除税收组

            DB::commit();
            return $info;

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return false;
        }
    }

    //名称是否重复
    public function isRepeat($reasondescription)
    {
        return CreditStatus::where('reasondescription', $reasondescription)->first();
    }
}
