<?php
namespace App\Repositories\AccountGroup;

use App\AccountGroup;
use App\Repositories\AccountGroup\AccountGroupRepositoryInterface;
use App\Repositories\BaseInterface\Repository;
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

class AccountGroupRepository implements AccountGroupRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id', 'groupname', 'pid', 'sectioninaccounts', 'pandl', 'sequenceintb', 'parentgroupname', 'status'];

    // 根据ID获得信息
    public function find($id)
    {
        return AccountGroup::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new AccountGroup(); // 返回的是一个Order实例,两种方法均可
        // $query = $query->addCondition($queryList); //根据条件组合语句
        $query = $query->where('status', '1')->orderBy('id', 'DESC');
        $query = $query->with('belongsToAccountSection', 'belongsToAccountGroup:id,groupname');
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

            $newGroup = new AccountGroup(); //税目

            $input = array_replace($requestData->all());

            if ($input['topGroup']) {
                //顶层组设置pid为0
                $input['pid'] = 0;
            }

            $input = nullDel($input);
            $newGroup->fill($input);

            $newGroup = $newGroup->create($input);

            DB::commit();
            return $newGroup;

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
        $info = AccountGroup::select($this->select_columns)->findorFail($id); //获取信息

        $info->groupname         = $requestData->groupname;
        $info->sectioninaccounts = $requestData->sectioninaccounts;
        $info->pandl             = $requestData->pandl;
        $info->sequenceintb      = $requestData->sequenceintb;

        if ($requestData->topGroup) {
            //顶层组设置pid为0
            $info->pid = 0;
        } else {
            $info->pid = $requestData->pid;
        }

        // dd($info);

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info = AccountGroup::findorFail($id);

            $returnData['status']  = true;
            $returnData['message'] = '';
            // dd($info->hasManyChartMasters->count());
            if ($info->hasManyChartMasters->count() > 0) {
                $returnData['status']  = false;
                $returnData['message'] = '不能删除，已创建了属于这个科目组的会计科目';

                return $returnData;
            }

            $info->status = '0'; //删除税目
            $info->save();

            DB::commit();
            return $returnData;

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return false;
        }
    }

    //名称是否重复
    public function isRepeat($groupname)
    {
        return AccountGroup::where('groupname', $groupname)->where('status', '1')->first();
    }
}
