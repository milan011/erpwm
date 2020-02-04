<?php
namespace App\Repositories\PcClaimExpensesFromTab;

use App\PcAssignCashToTab;
use App\PcTab;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\PcClaimExpensesFromTab\PcClaimExpensesFromTabRepositoryInterface;
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

class PcClaimExpensesFromTabRepository implements PcClaimExpensesFromTabRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['counterindex', 'tabcode', 'date', 'codeexpense', 'amount', 'authorized', 'posted', 'notes', 'receipt', 'status'];

    // 根据ID获得信息
    public function find($id)
    {
        return PcAssignCashToTab::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $user_id     = Auth::user()->id;
        $assign_tabs = PcTab::where('usercode', $user_id)->get();

        if ($assign_tabs->count() == 0) {
            //当前用户无分配标签,则返回空
            return [];
        }

        //分配给用户的标签列表
        $assign_tab_list = [];
        foreach ($assign_tabs as $key => $value) {
            $assign_tab_list[] = $value->id;
        }

        //分配给标签的费用列表
        // $list = $assign_tabs[1]->hasManyPcexpenses;
        // dd(lastSql());
        // dd($assign_tabs[1]->hasManyPcexpenses);

        $assing_expense_list = [];
        foreach ($assign_tabs as $key => $value) {
            if (!empty($value->hasManyPcexpenses)) {
                foreach ($value->hasManyPcexpenses as $k => $v) {
                    $assing_expense_list[] = $v->id;
                }
            }
        }

        $assing_expense_list = array_unique($assing_expense_list);
        // dd($assing_expense_list);
        // dd($assign_tab_list);
        // $queryList['codeexpense'] = $assing_expense_list;

        $query = new PcAssignCashToTab(); // 返回的是一个Order实例,两种方法均可
        $query = $query->addCondition($queryList); //根据条件组合语句
        $query = $query->where('status', '1')->orderBy('counterindex', 'DESC');
        $query = $query->whereIn('tabcode', $assign_tab_list);
        if (!empty($assing_expense_list) && empty($queryList['codeexpense'])) {
            $query = $query->whereIn('codeexpense', $assing_expense_list);
        }
        $query = $query->with('belongsToPcTab:id,tabcode,assigner', 'belongsToPcExpenses:id,description');

        if (empty($queryList['page'])) {
            //无分页,全部返还
            return $query->get();
        } else {
            return $query->paginate(10);
        }
    }

    // 获取登录用户可分配的费用列表
    public function getExpensesList($queryList)
    {
        // dd($queryList);
        $user_id = Auth::user()->id;
        if (!empty($queryList['tabId'])) {
            $assign_tabs = PcTab::where('id', $queryList['tabId'])->first();
            // dd($assign_tabs->hasManyPcexpenses);
            return $assign_tabs->hasManyPcexpenses;
        } else {
            $assign_tabs = PcTab::where('usercode', $user_id)->get();
        }
        // dd(lastSql());
        if ($assign_tabs->count() == 0) {
            //当前用户无分配标签,则返回空
            return [];
        }

        //分配给用户的标签列表
        $assign_tab_list = [];
        foreach ($assign_tabs as $key => $value) {
            $assign_tab_list[] = $value->id;
        }

        //分配给标签的费用列表
        // $list = $assign_tabs[1]->hasManyPcexpenses;
        // dd(lastSql());
        // dd($assign_tabs[1]->hasManyPcexpenses);
        // dd($assign_tabs);
        $assing_expense_list = [];
        foreach ($assign_tabs as $key => $value) {
            if (!empty($value->hasManyPcexpenses)) {
                foreach ($value->hasManyPcexpenses as $k => $v) {
                    $assing_expense_list[] = $v->all();
                }
            }
        }

        // dd($assing_expense_list);

        $assing_expense_list = collect($assing_expense_list);
        $list                = $assing_expense_list->unique();
        /*dd($list);
        $assing_expense_list = array_unique($assing_expense_list);*/

        return $list[0];
    }

    // 创建信息
    public function create($requestData)
    {

        DB::beginTransaction();
        try {

            $example = new PcAssignCashToTab(); //税目

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
        $info = PcAssignCashToTab::select($this->select_columns)->findorFail($id); //获取信息

        $info->tabcode     = $requestData->tabcode;
        $info->codeexpense = $requestData->codeexpense;
        $info->date        = $requestData->date;
        $info->amount      = $requestData->amount;
        $info->notes       = $requestData->notes;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info         = PcAssignCashToTab::findorFail($id);
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
        return PcAssignCashToTab::where('taxcatname', $taxcatname)->where('status', '1')->first();
    }
}
