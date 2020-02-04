<?php
namespace App\Repositories\PcExpenses;

use App\PcExpenses;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\PcExpenses\PcExpensesRepositoryInterface;
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

class PcExpensesRepository implements PcExpensesRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id', 'description', 'glaccount', 'tag'];

    // 根据ID获得信息
    public function find($id)
    {
        return PcExpenses::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new PcExpenses(); // 返回的是一个Order实例,两种方法均可
        $query = $query->where('status', '1')->orderBy('id', 'DESC');
        $query = $query->with('belongsToTags', 'belongsToChartMaster');
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

            $example = new PcExpenses(); //税目

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
        $info = PcExpenses::select($this->select_columns)->findorFail($id); //获取信息

        $info->description = $requestData->description;
        $info->glaccount   = $requestData->glaccount;
        $info->tag         = $requestData->tag;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info         = PcExpenses::findorFail($id);
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
        return PcExpenses::where('description', $description)->where('status', '1')->first();
    }
}
