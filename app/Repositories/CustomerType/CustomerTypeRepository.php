<?php
namespace App\Repositories\CustomerType;

use App\CustomerType;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\CustomerType\CustomerTypeRepositoryInterface;
use App\TaxGroupTaxes;
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

class CustomerTypeRepository implements CustomerTypeRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['typeid', 'typename', 'status'];

    // 根据ID获得信息
    public function find($id)
    {
        return CustomerType::select($this->select_columns)->with('hasManyTaxGroupTaxes')
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new CustomerType(); // 返回的是一个Order实例,两种方法均可
        $query = $query->where('status', '1')->orderBy('typeid', 'DESC');
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

            $sale_type = new CustomerType(); //税目

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

        $info = CustomerType::select($this->select_columns)->findorFail($id); //获取信息

        $info->typename = $requestData->typename;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info         = CustomerType::findorFail($id);
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
    public function isRepeat($typename)
    {
        return CustomerType::where('typename', $typename)->first();
    }
}
