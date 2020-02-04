<?php
namespace App\Repositories\FixedAssetLocation;

use App\FixedAssetLocation;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\FixedAssetLocation\FixedAssetLocationRepositoryInterface;
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

class FixedAssetLocationRepository implements FixedAssetLocationRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id', 'locationid', 'locationdescription', 'parentlocationid'];

    // 根据ID获得信息
    public function find($id)
    {
        return FixedAssetLocation::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new FixedAssetLocation(); // 返回的是一个Order实例,两种方法均可
        $query = $query->addCondition($queryList); //根据条件组合语句
        $query = $query->where('status', '1')->orderBy('id', 'DESC');
        $query = $query->with('belongsToFixedAssetLocation');
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

            $example = new FixedAssetLocation(); //税目

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
        $info = FixedAssetLocation::select($this->select_columns)->findorFail($id); //获取信息

        $info->locationdescription = $requestData->locationdescription;
        $info->parentlocationid    = $requestData->parentlocationid;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info = FixedAssetLocation::findorFail($id);

            $locationChild = $info->hasManyFixedAssetLocation->count();
            $locationFiexd = $info->hasManyFixedAssets->count();

            if (($locationChild > 0) || ($locationFiexd > 0)) {
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
    public function isRepeat($locationdescription)
    {
        return FixedAssetLocation::where('locationdescription', $locationdescription)->where('status', '1')->first();
    }
}
