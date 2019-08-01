<?php
namespace App\Repositories\StockCatProperties;

use App\Repositories\BaseInterface\Repository;
use App\Repositories\StockCatProperties\StockCatPropertiesRepositoryInterface;
use App\StockCatProperties;
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

class StockCatPropertiesRepository implements StockCatPropertiesRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['stkcatpropid', 'categoryid', 'label', 'controltype', 'defaultvalue', 'maximumvalue', 'reqatsalesorder', 'minimumvalue', 'numericvalue'];

    // 根据ID获得信息
    public function find($id)
    {
        return StockCatProperties::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new StockCatProperties(); // 返回的是一个Order实例,两种方法均可

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

            $example = new StockCatProperties(); //税目

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
        $info = StockCatProperties::select($this->select_columns)->findorFail($id); //获取信息

        $info->taxcatname = $requestData->taxcatname;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info         = StockCatProperties::findorFail($id);
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
        return StockCatProperties::where('taxcatname', $taxcatname)->where('status', '1')->first();
    }
}
