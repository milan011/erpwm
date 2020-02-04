<?php
namespace App\Repositories\MaintenanceTask;

use App\MaintenanceTask;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\MaintenanceTask\MaintenanceTaskRepositoryInterface;
use Auth;
use Carbon;
use Datatables;
use DB;
use Debugbar;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PHPZen\LaravelRbac\Traits\Rbac;
use Planbon;
use Session;

class MaintenanceTaskRepository implements MaintenanceTaskRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['taskid', 'assetid', 'taskdescription', 'frequencydays', 'lastcompleted', 'userresponsible', 'manager', 'status'];

    // 根据ID获得信息
    public function find($id)
    {
        return MaintenanceTask::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new MaintenanceTask(); // 返回的是一个Order实例,两种方法均可
        // $query = $query->addCondition($queryList); //根据条件组合语句
        $query = $query->where('status', '1')->orderBy('taskid', 'DESC');
        $query = $query->with('belongsToFixedAssetItem:assetid,description', 'belongsToUserWithUserresponsible:id,realname', 'belongsToUserWithManager:id,realname');
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

            $example = new MaintenanceTask(); //税目

            $input                  = array_replace($requestData->all());
            $input['lastcompleted'] = Carbon::now()->toDateString();
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
        $info = MaintenanceTask::select($this->select_columns)->findorFail($id); //获取信息

        $info->assetid         = $requestData->assetid;
        $info->taskdescription = $requestData->taskdescription;
        $info->frequencydays   = $requestData->frequencydays;
        // $info->lastcompleted   = $requestData->lastcompleted;
        $info->userresponsible = $requestData->userresponsible;
        $info->manager         = $requestData->manager;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info         = MaintenanceTask::findorFail($id);
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
    public function isRepeat($taskdescription)
    {
        return MaintenanceTask::where('taskdescription', $taskdescription)->where('status', '1')->first();
    }
}
