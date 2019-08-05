<?php
namespace App\Repositories\Custbranch;

use App\Custbranch;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\Custbranch\CustbranchRepositoryInterface;
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

class CustbranchRepository implements CustbranchRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id', 'branchcode', 'debtorno', 'brname', 'braddress1', 'braddress2', 'braddress3', 'braddress4', 'braddress5', 'braddress6', 'lat', 'lng', 'estdeliverydays', 'area', 'salesman', 'fwddate', 'phoneno', 'faxno', 'contactname', 'email', 'defaultlocation', 'taxgroupid', 'defaultshipvia', 'deliverblind', 'disabletrans', 'brpostaddr1', 'brpostaddr2', 'brpostaddr3', 'brpostaddr4', 'brpostaddr5', 'brpostaddr6', 'custbranchcode', 'specialinstructions'];

    // 根据ID获得信息
    public function find($id)
    {
        return Custbranch::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new Custbranch(); // 返回的是一个Order实例,两种方法均可

        if (empty($queryList['page'])) {
            //无分页,全部返还
            $query = $query->where('debtorno', $queryList['deb_id']);

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

            $example = new Custbranch(); //税目

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
        $info = Custbranch::select($this->select_columns)->findorFail($id); //获取信息

        $info->taxcatname = $requestData->taxcatname;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info         = Custbranch::findorFail($id);
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
        return Custbranch::where('taxcatname', $taxcatname)->where('status', '1')->first();
    }
}
