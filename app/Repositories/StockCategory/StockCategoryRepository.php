<?php
namespace App\Repositories\StockCategory;

use App\Repositories\BaseInterface\Repository;
use App\Repositories\StockCategory\StockCategoryRepositoryInterface;
use App\StockCategory;
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

class StockCategoryRepository implements StockCategoryRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id', 'categoryid', 'categorydescription', 'stocktype', 'stockact', 'adjglact', 'issueglact', 'purchpricevaract', 'materialuseagevarac', 'wipact', 'defaulttaxcatid'];

    // 根据ID获得信息
    public function find($id)
    {
        return StockCategory::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new StockCategory(); // 返回的是一个Order实例,两种方法均可
        $query = $query->with('belongsToTaxCategories', 'belongsToChartMasterWithStockact', 'belongsToChartMasterWithWipact', 'belongsToChartMasterWithAdjglact', 'belongsToChartMasterWithIssueglact', 'belongsToChartMasterWithPurchpricevaract', 'belongsToChartMasterWithMaterialuseagevarac', 'hasManyStockCatProperties', 'hasManyStockMaster')
            ->where('status', '1')
            ->orderBy('id', 'DESC');
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

            $example = new StockCategory(); //税目

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
        $info = StockCategory::select($this->select_columns)->findorFail($id); //获取信息

        $info->categorydescription = $requestData->categorydescription;
        $info->stocktype           = $requestData->stocktype;
        $info->stockact            = $requestData->stockact;
        $info->adjglact            = $requestData->adjglact;
        $info->issueglact          = $requestData->issueglact;
        $info->purchpricevaract    = $requestData->purchpricevaract;
        $info->materialuseagevarac = $requestData->materialuseagevarac;
        $info->wipact              = $requestData->wipact;
        $info->defaulttaxcatid     = $requestData->defaulttaxcatid;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info         = StockCategory::findorFail($id);
            $info->status = '0'; //删除税目
            $info->save();

            $num_stock = $info->hasManyStockMaster->count();
            $num_sale  = $info->hasManySalesGLPosting->count();
            $num_co    = $info->hasManyCOGSGLPosting->count();

            /*p($num_stock);
            p($num_sale);
            dd($num_co);*/
            if ($num_stock > 0 || $num_sale > 0 || $num_co > 0) {
                return false;
            }

            $info->hasManyStockCatProperties()->delete();

            DB::commit();
            return $info;

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return false;
        }
    }

    //名称是否重复
    public function isRepeat($categorydescription)
    {
        return StockCategory::where('categorydescription', $categorydescription)->where('status', '1')->first();
    }
}
