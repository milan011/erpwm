<?php
namespace App\Repositories\FixedAssetCategorie;

use App\FixedAssetCategorie;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\FixedAssetCategorie\FixedAssetCategorieRepositoryInterface;
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

class FixedAssetCategorieRepository implements FixedAssetCategorieRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id', 'categorydescription', 'costact', 'depnact', 'disposalact', 'accumdepnact', 'defaultdepnrate', 'defaultdepntype', 'status'];

    // 根据ID获得信息
    public function find($id)
    {
        return FixedAssetCategorie::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new FixedAssetCategorie(); // 返回的是一个Order实例,两种方法均可
        $query = $query->addCondition($queryList); //根据条件组合语句
        $query = $query->with('belongsToChartMasterWithAccumdepnact', 'belongsToChartMasterWithDisposalact', 'belongsToChartMasterWithCostact', 'belongsToChartMasterWithDepnact');
        $query = $query->where('status', '1')->orderBy('id', 'DESC');
        // $query = $query->with('');
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

            $example = new FixedAssetCategorie(); //税目

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
        $info = FixedAssetCategorie::select($this->select_columns)->findorFail($id); //获取信息

        $info->categorydescription = $requestData->categorydescription;
        $info->costact             = $requestData->costact;
        $info->depnact             = $requestData->depnact;
        $info->disposalact         = $requestData->disposalact;
        $info->accumdepnact        = $requestData->accumdepnact;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info = FixedAssetCategorie::findorFail($id);

            //是否有fixed存在
            $count = $info->hasManyFixedAssets->count();

            if ($count > 0) {
                return false;
            }
            // dd($count);

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
    public function isRepeat($categorydescription)
    {
        return FixedAssetCategorie::where('categorydescription', $categorydescription)->where('status', '1')->first();
    }
}
