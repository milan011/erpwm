<?php
namespace App\Repositories\PcAuthorizeExpense;

use App\BankTrans;
use App\Gltrans;
use App\PcAssignCashToTab;
use App\PcTab;
use App\Periods;
use App\Repositories\BankTrans\BankTransRepositoryInterface;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\Gltrans\GltransRepositoryInterface;
use App\Repositories\PcAuthorizeExpense\PcAuthorizeExpenseRepositoryInterface;
use App\Repositories\SysType\SysTypeRepositoryInterface;
use App\SysType;
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

class PcAuthorizeExpenseRepository implements PcAuthorizeExpenseRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['counterindex', 'tabcode', 'date', 'codeexpense', 'amount', 'authorized', 'posted', 'notes', 'receipt', 'status'];

    protected $gltrans;
    protected $bankTrans;
    protected $sysType;

    public function __construct(

        BankTransRepositoryInterface $gltrans,
        SysTypeRepositoryInterface $sysType,
        GltransRepositoryInterface $bankTrans
    ) {
        $this->gltrans   = $gltrans;
        $this->bankTrans = $bankTrans;
        $this->sysType   = $sysType;
    }

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
        /*if (!empty($assing_expense_list) && empty($queryList['codeexpense'])) {
        $query = $query->whereIn('codeexpense', $assing_expense_list);
        }*/
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

    // 审批费用
    public function approval($id)
    {
        DB::beginTransaction();
        try {
            $info = PcAssignCashToTab::with('belongsToPcTab', 'belongsToPcExpenses')
                ->findorFail($id);
            $sysType = new SysType();
            $gltrans = new Gltrans();

            // dd($info);
            /*
            记录审批记录至Gltrans及BankTrans
             */
            $gltransInfo = [];

            $gltransInfo['trandate'] = $info->date;

            /*记录Gltrans*/
            //确定会计期间
            $assignDate = $info->date; //申请日期
            $nextMonth  = Carbon::parse($info->date)->addMonths(1)->toDateString();

            $periodNo                = Periods::whereBetween('lastdate_in_period', [$assignDate, $nextMonth])->first()->periodno;
            $gltransInfo['periodno'] = $periodNo;

            //确定
            /*dd($periodNo);
            p($assignDate);
            dd($nextMonth);
            $dt->addMonths(60);*/
            /*根据codeexpense确认gltrans的type等数据*/
            if ($info->codeexpense == 'ASSIGNCASH') {
                //预付现金
                $gltransInfo['type']        = 2;
                $gltransInfo['tag']         = 0;
                $gltransInfo['amount']      = -$info->amount;
                $gltransInfo['accountFrom'] = $info->belongsToPcTab->glaccountassignment;
                $gltransInfo['accountTo']   = $info->belongsToPcTab->glaccountpcash;
            } else {
                //报销申请
                $gltransInfo['type']        = 1;
                $gltransInfo['amount']      = $info->amount;
                $gltransInfo['accountFrom'] = $info->belongsToPcTab->glaccountpcash;
                $gltransInfo['accountTo']   = $info->belongsToPcExpenses->glaccount;
                $gltransInfo['tag']         = $info->belongsToPcExpenses->tag;
            }

            /*处理标签代码*/
            $sysInfo               = $sysType->where('typeid', $gltransInfo['type'])->first();
            $gltransInfo['typeno'] = $sysInfo->typeno + 1;
            //

            /*摘要内容*/
            $gltransInfo['narrative'] = '小额现金-';
            $gltransInfo['narrative'] .= $info->belongsToPcTab->tabcode;
            $gltransInfo['narrative'] .= '-';
            $gltransInfo['narrative'] .= $info->belongsToPcExpenses->description;
            $gltransInfo['narrative'] .= '-';
            $gltransInfo['narrative'] .= $info->notes;
            $gltransInfo['narrative'] .= '-';
            $gltransInfo['narrative'] .= $info->receipt;

            //glFrom新增
            $gltransInfoFrom            = $gltransInfo;
            $gltransInfoFrom['account'] = $gltransInfo['accountFrom'];

            //glTo新增
            $gltransInfoTo            = $gltransInfo;
            $gltransInfoTo['account'] = $gltransInfo['accountTo'];

            /*p($gltransInfoFrom);
            dd($gltransInfoTo);*/

            /*新增Gltrans*/
            $gltransF = $gltrans->create($gltransInfoFrom);
            $gltransT = $gltrans->create($gltransInfoTo);
            // dd($gltransF);

            /*新增BankTrans*/
            $bankTransInfo = [];

            if ($info->codeexpense == 'ASSIGNCASH') {
                //预付现金
                $bankTrans = new BankTrans();
                $bankTrans->create();
                dd($bankTrans);
                /*处理标签代码*/
                // $sysInfo = $sysType->where('typeid', 2)->first();

                // $bankTransInfo['transno'] = $sysInfo->typeno + 2;
                // $bankTransInfo['transno'] = 2;
                /*$bankTransInfo['type']          = 1;
                $bankTransInfo['bankact']       = $gltransInfo['accountFrom'];
                $bankTransInfo['ref']           = $gltransInfo['narrative'];
                $bankTransInfo['exrate']        = 1;
                $bankTransInfo['transdate']     = $info->date;
                $bankTransInfo['banktranstype'] = 'Cash';
                $bankTransInfo['currcode']      = 'CNY';
                $bankTransInfo['amount']        = -$info->amount;*/
                // dd($bankTrans);
                $gltransssT = $bankTrans->create($bankTransInfo);
                // $gltransT   = $gltrans->create([$gltransInfoTo]);
                dd(lastSql());
            }
            // dd($bankTransInfo);
            // dd('hehe');
            // $bankTrans = $bankTrans->create($bankTransInfo);
            dd(lastSql());
            dd($bankTrans);

            $info->posted     = '2'; //审批通过
            $info->authorized = Carbon::now()->toDateString(); //审批通过
            $info->save();
            $sysInfo->typeno = $bankTransInfo['typeno'];
            $sysInfo->save();

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
