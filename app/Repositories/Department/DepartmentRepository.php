<?php
namespace App\Repositories\Department;

use App\Department;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\Department\DepartmentRepositoryInterface;
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

class DepartmentRepository implements DepartmentRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['departmentid', 'description', 'authoriser'];

    // 根据ID获得信息
    public function find($id)
    {
        return Department::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new Department(); // 返回的是一个Order实例,两种方法均可
        $query = $query->with('belongsToUser')->where('status', '1')->orderBy('departmentid', 'DESC');
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

            $example = new Department(); //税目

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
        $info = Department::select($this->select_columns)->findorFail($id); //获取信息

        $info->description = $requestData->description;
        $info->authoriser  = $requestData->authoriser;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info         = Department::findorFail($id);
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
    public function isRepeat($description)
    {
        return Department::where('description', $description)->where('status', '1')->first();
    }
}
