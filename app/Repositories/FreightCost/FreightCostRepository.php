<?php
namespace App\Repositories\FreightCost;

use App\FreightCost;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\FreightCost\FreightCostRepositoryInterface;
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

class FreightCostRepository implements FreightCostRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['shipcostfromid', 'locationfrom', 'destinationcountry', 'destination', 'shipperid', 'cubrate', 'kgrate', 'maxkgs', 'maxcub', 'fixedprice'];

    // 根据ID获得信息
    public function find($id)
    {
        return FreightCost::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new FreightCost(); // 返回的是一个Order实例,两种方法均可
        $query = $query->with('belongsToShipper', 'belongsToLocations')->where('status', '1')->orderBy('shipcostfromid', 'DESC');

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

            $example = new FreightCost(); //税目

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
        $info = FreightCost::select($this->select_columns)->findorFail($id); //获取信息

        $info->locationfrom = $requestData->locationfrom;
        $info->destination  = $requestData->destination;
        $info->shipperid    = $requestData->shipperid;
        $info->cubrate      = $requestData->cubrate;
        $info->kgrate       = $requestData->kgrate;
        $info->maxkgs       = $requestData->maxkgs;
        $info->maxcub       = $requestData->maxcub;
        $info->fixedprice   = $requestData->fixedprice;
        $info->minimumchg   = $requestData->minimumchg;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info         = FreightCost::findorFail($id);
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
        return FreightCost::where('taxcatname', $taxcatname)->where('status', '1')->first();
    }
}
