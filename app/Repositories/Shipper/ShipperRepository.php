<?php
namespace App\Repositories\Shipper;

use App\Repositories\BaseInterface\Repository;
use App\Repositories\Shipper\ShipperRepositoryInterface;
use App\Shipper;
use App\SystemParameter;
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

class ShipperRepository implements ShipperRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['shipper_id', 'shippername', 'mincharge'];

    // 根据ID获得信息
    public function find($id)
    {
        return Shipper::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new Shipper(); // 返回的是一个Order实例,两种方法均可
        $query = $query->where('status', '1')->orderBy('shipper_id', 'DESC');

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

            $example = new Shipper(); //税目

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
    public function update($requestData, $id)
    {
        // dd($requestData->all());
        $info = Shipper::select($this->select_columns)->findorFail($id); //获取信息

        $info->shippername = $requestData->shippername;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info    = Shipper::findorFail($id);
            $num_cus = $info->hasManySalesOrders->count();
            $num_ana = $info->hasManySalesDebtorTrans;
            // $num_ana = $info->hasManySalesDebtorTrans->count();
            // dd(lastSql());
            $default_shipper = SystemParameter::where('confname', 'DefaultSupplierType')->first();
            // dd($default_shipper);
            /*p($num_cus);
            p($num_user);*/
            // dd($num_cus);
            if ($num_cus > 0 || $num_ana > 0 || $default_shipper->confvalue == $id) {
                return false;
            }
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
    public function isRepeat($shippername)
    {
        return Shipper::where('shippername', $shippername)->where('status', '1')->first();
    }
}
