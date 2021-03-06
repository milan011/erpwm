<?php
namespace App\Repositories\SalesGLPosting;

use App\Repositories\BaseInterface\Repository;
use App\Repositories\SalesGLPosting\SalesGLPostingRepositoryInterface;
use App\SalesGLPosting;
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

class SalesGLPostingRepository implements SalesGLPostingRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id', 'area', 'stkcat', 'discountglcode', 'salesglcode', 'salestype', 'status'];

    // 根据ID获得信息
    public function find($id)
    {
        return SalesGLPosting::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new SalesGLPosting(); // 返回的是一个Order实例,两种方法均可
        $query = $query->with('belongsToArea', 'belongsToStockCategory', 'belongsToChartMasterWithSalesglCode', 'belongsToChartMasterWithDiscountglCode', 'belongsToSaleType');
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

            $example = new SalesGLPosting(); //税目

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
        $info = SalesGLPosting::select($this->select_columns)->findorFail($id); //获取信息

        $info->area           = $requestData->area;
        $info->stkcat         = $requestData->stkcat;
        $info->discountglcode = $requestData->discountglcode;
        $info->salesglcode    = $requestData->salesglcode;
        $info->salestype      = $requestData->salestype;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info         = SalesGLPosting::findorFail($id);
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
    public function isRepeat($area, $stkcat, $salestype)
    {
        return SalesGLPosting::where('area', $area)
            ->where('stkcat', $stkcat)
            ->where('salestype', $salestype)
            ->where('status', '1')
            ->first();
    }
}
