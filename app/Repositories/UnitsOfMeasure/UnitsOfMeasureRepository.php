<?php
namespace App\Repositories\UnitsOfMeasure;

use App\Repositories\BaseInterface\Repository;
use App\Repositories\UnitsOfMeasure\UnitsOfMeasureRepositoryInterface;
use App\UnitsOfMeasure;
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

class UnitsOfMeasureRepository implements UnitsOfMeasureRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['unitid', 'unitname'];

    // 根据ID获得信息
    public function find($id)
    {
        return UnitsOfMeasure::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new UnitsOfMeasure(); // 返回的是一个Order实例,两种方法均可
        $query = $query->orderBy('unitid', 'DESC');
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

            $units = new UnitsOfMeasure(); //税目

            $input = array_replace($requestData->all());
            $units->fill($input);
            $units = $units->create($input);

            DB::commit();
            return $units;

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
        $info = UnitsOfMeasure::select($this->select_columns)->findorFail($id); //获取信息

        $info->unitname = $requestData->unitname;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info = UnitsOfMeasure::findorFail($id);
            $info->delete();

            DB::commit();
            return $info;

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return false;
        }
    }

    //名称是否重复
    public function isRepeat($unitname)
    {
        return UnitsOfMeasure::where('unitname', $unitname)->first();
    }
}
