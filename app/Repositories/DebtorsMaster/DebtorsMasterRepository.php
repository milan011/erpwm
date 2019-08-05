<?php
namespace App\Repositories\DebtorsMaster;

use App\DebtorsMaster;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\DebtorsMaster\DebtorsMasterRepositoryInterface;
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

class DebtorsMasterRepository implements DebtorsMasterRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id', 'debtorno', 'name', 'address1', 'address2', 'address3', 'address4', 'address5', 'address6', 'salestype', 'currcode', 'clientsince', 'paymentterms', 'holdreason', 'discount', 'pymtdiscount', 'lastpaid', 'lastpaiddate', 'creditlimit', 'invaddrbranch', 'discountcode', 'ediinvoices', 'ediorders', 'edireference', 'editransport', 'ediaddress', 'ediserveruser', 'ediserverpwd', 'taxref', 'customerpoline', 'typeid', 'language_id'];

    // 根据ID获得信息
    public function find($id)
    {
        return DebtorsMaster::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new DebtorsMaster(); // 返回的是一个Order实例,两种方法均可

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

            $example = new DebtorsMaster(); //税目

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
        $info = DebtorsMaster::select($this->select_columns)->findorFail($id); //获取信息

        $info->taxcatname = $requestData->taxcatname;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info         = DebtorsMaster::findorFail($id);
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
    public function isRepeat($taxcatname)
    {
        return DebtorsMaster::where('taxcatname', $taxcatname)->where('status', '1')->first();
    }
}
