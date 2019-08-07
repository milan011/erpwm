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
        $query = $query->with('belongsToPcTypeTab', 'belongsToChartMasterWithAssignment', 'belongsToChartMasterWithCash', 'belongsToUserWithAssign:id,realname');
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

            $example = new PcTab(); //税目

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
        $info = PcTab::select($this->select_columns)->findorFail($id); //获取信息

        $info->taxcatname = $requestData->taxcatname;

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
    public function isRepeat($taxcatname)
    {
        return PcTab::where('taxcatname', $taxcatname)->where('status', '1')->first();
    }
}
