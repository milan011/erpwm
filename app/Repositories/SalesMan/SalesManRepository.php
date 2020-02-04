<?php
namespace App\Repositories\SalesMan;

use App\Repositories\BaseInterface\Repository;
use App\Repositories\SalesMan\SalesManRepositoryInterface;
use App\SalesMan;
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

class SalesManRepository implements SalesManRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id', 'salesmanname', 'smantel', 'smanfax', 'commissionrate1', 'breakpoint', 'commissionrate2', 'current', 'status'];

    // 根据ID获得信息
    public function find($id)
    {
        return SalesMan::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new SalesMan(); // 返回的是一个Order实例,两种方法均可
        $query = $query->where('status', '1');
        $query = $query->orderBy('id', 'DESC');
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

            $sales_man = new SalesMan(); //销售

            $input = array_replace($requestData->all());
            $sales_man->fill($input);
            $sales_man = $sales_man->create($input);
            DB::commit();
            return $sales_man;

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
        $info = SalesMan::select($this->select_columns)->findorFail($id); //获取信息

        $info->salesmanname    = $requestData->salesmanname;
        $info->smantel         = $requestData->smantel;
        $info->smanfax         = nullToEmptyString($requestData->smanfax);
        $info->commissionrate1 = $requestData->commissionrate1;
        $info->breakpoint      = $requestData->breakpoint;
        $info->current         = $requestData->current;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info     = SalesMan::findorFail($id);
            $num_cus  = $info->hasManyCustbranch->count();
            $num_user = $info->hasManyUser->count();
            $num_ana  = $info->hasManySalesAnalysis->count();

            /*p($num_cus);
            p($num_user);
            dd($num_ana);*/
            if ($num_cus > 0 || $num_user > 0 || $num_ana > 0) {
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
    public function isRepeat($smantel)
    {
        return SalesMan::where('smantel', $smantel)->where('status', '1')->first();
    }
}
