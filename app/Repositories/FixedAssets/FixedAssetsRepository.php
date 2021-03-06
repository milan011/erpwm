<?php
namespace App\Repositories\FixedAssets;

use App\FixedAssets;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\FixedAssets\FixedAssetsRepositoryInterface;
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

class FixedAssetsRepository implements FixedAssetsRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['assetid', 'serialno', 'barcode', 'assetlocation', 'cost', 'accumdepn', 'datepurchased', 'disposalproceeds', 'assetcategoryid', 'description', 'longdescription', 'depntype', 'depnrate', 'disposaldate', 'status'];

    // 根据ID获得信息
    public function find($id)
    {
        return FixedAssets::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new FixedAssets(); // 返回的是一个Order实例,两种方法均可
        $query = $query->addCondition($queryList); //根据条件组合语句
        $query = $query->where('status', '1')->orderBy('assetid', 'DESC');
        $query = $query->with('belongsToFixedAssetCategorie:id,categorydescription', 'belongsToFixedAssetLocation:id,locationdescription');
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

            $assets = new FixedAssets(); //税目

            $input = array_replace($requestData->all());
            $input = nullDel($input);

            $assets->fill($input);
            $assets = $assets->create($input);

            DB::commit();
            return $assets;

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
        $info = FixedAssets::select($this->select_columns)->findorFail($id); //获取信息

        $info->description     = $requestData->description;
        $info->longdescription = $requestData->longdescription;
        $info->assetcategoryid = $requestData->assetcategoryid;
        $info->assetlocation   = $requestData->assetlocation;
        $info->serialno        = !empty($requestData->serialno) ? $requestData->serialno : '';
        $info->barcode         = !empty($requestData->barcode) ? $requestData->barcode : '';
        $info->depntype        = $requestData->depntype;
        $info->depnrate        = $requestData->depnrate;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info                  = FixedAssets::findorFail($id);
            $returnData['status']  = true;
            $returnData['message'] = '';

            // dd($info->hasManyFixedAssetTrans->count());
            $nbv = $info->cost - $info->accumdepn;

            if ($nbv != 0) {
                $returnData['status']  = false;
                $returnData['message'] = '此资产依然有帐目净值。只有帐目净值为0的资产才可以删除';

                return $returnData;
            }

            if ($info->hasManyFixedAssetTrans->count() > 0) {
                $returnData['status']  = false;
                $returnData['message'] = '此资产有关联交易。只有固定资产的交易被删除后，才可删除此资产，否则的话资产报告的完整性会被破坏';

                return $returnData;
            }

            if ($info->hasManyPurchOrderDetails->count() > 0) {
                $returnData['status']  = false;
                $returnData['message'] = '此资产已经有一张采购订单。必须先删除采购订单行';

                return $returnData;
            }

            $info->status = '0'; //删除资产
            $info->save();

            DB::commit();
            return $returnData;

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return false;
        }
    }

    //名称是否重复
    public function isRepeat($description)
    {
        return FixedAssets::where('description', $description)->where('status', '1')->first();
    }
}
