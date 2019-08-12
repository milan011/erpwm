<?php
namespace App\Repositories\PcTab;

use App\PcTab;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\PcTab\PcTabRepositoryInterface;
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

class PcTabRepository implements PcTabRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id', 'tabcode', 'usercode', 'typetabcode', 'currency', 'tablimit', 'assigner', 'authorizer', 'glaccountassignment', 'glaccountpcash'];

    // 根据ID获得信息
    public function find($id)
    {
        return PcTab::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new PcTab(); // 返回的是一个Order实例,两种方法均可
        $query = $query->where('status', '1')->orderBy('id', 'DESC');
        $query = $query->with('belongsToPcTypeTab', 'belongsToChartMasterWithAssignment', 'belongsToChartMasterWithCash', 'belongsToUserWithAssign:id,realname', 'belongsToUserWithUscode:id,realname', 'belongsUserWithAuthorizer:id,realname', 'hasManyPcexpenses');
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

            $tab = new PcTab(); //税目

            $input = array_replace($requestData->all());
            $tab->fill($input);
            $tab = $tab->create($input);

            DB::commit();
            return $tab;

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
        $info = PcTab::select($this->select_columns)->findorFail($id); //获取信息

        $info->tabcode             = $requestData->tabcode;
        $info->usercode            = $requestData->usercode;
        $info->typetabcode         = $requestData->typetabcode;
        $info->tablimit            = $requestData->tablimit;
        $info->assigner            = $requestData->assigner;
        $info->authorizer          = $requestData->authorizer;
        $info->glaccountassignment = $requestData->glaccountassignment;
        $info->glaccountpcash      = $requestData->glaccountpcash;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info         = PcTab::findorFail($id);
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
    public function isRepeat($tabcode)
    {
        return PcTab::where('tabcode', $tabcode)->where('status', '1')->first();
    }
}
