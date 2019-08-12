<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
// use App\Http\Resources\PcClaimExpensesFromTab\PcClaimExpensesFromTabResource;
// use App\Http\Resources\PcClaimExpensesFromTab\PcClaimExpensesFromTabResourceCollection;
use App\Repositories\PcClaimExpensesFromTab\PcClaimExpensesFromTabRepositoryInterface;
use Illuminate\Http\Request;

class PcClaimExpensesFromTabController extends Controller
{
    protected $pcClaimExpensesFromTab;

    public function __construct(

        PcClaimExpensesFromTabRepositoryInterface $pcClaimExpensesFromTab
    ) {
        $this->pcClaimExpensesFromTab = $pcClaimExpensesFromTab;
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query_list = jsonToArray($request); //获取搜索信息

        $pcClaimExpensesFromTabs = $this->pcClaimExpensesFromTab->getList($query_list);
        // dd(lastSql());
        return $pcClaimExpensesFromTabs;
    }

    // 获取登录用户可分配的费用列表
    public function getExpenses(Request $request)
    {
        $query_list = jsonToArray($request); //获取搜索信息
        // dd($query_list);
        $assing_expense_list = $this->pcClaimExpensesFromTab->getExpensesList($query_list);

        return $assing_expense_list;
    }

    /**
     * Show the form for creating a new resource.
     * 创建(基于接口的开发方式不需要该方法)
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * 存储
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $info = $this->pcClaimExpensesFromTab->create($request);
        $info->belongsToPcTab;
        $info->belongsToPcExpenses;

        if ($info) {
            //添加成功
            return $this->baseSucceed($respond_data = $info, $message = '添加成功');
        } else {
            //添加失败
            return $this->baseFailed($message = '内部错误');
        }
    }

    /**
     * Display the specified resource.
     * 根据id获取特定实例
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $info = $this->pcClaimExpensesFromTab->find($id);
        $info->belongsToCreater;

        return new PcClaimExpensesFromTabResource($info);
    }

    /**
     * Show the form for editing the specified resource.
     * 编辑(基于接口的开发方式不需要该方法)
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());

        $info = $this->pcClaimExpensesFromTab->update($request, $id);
        $info->belongsToPcTab;
        $info->belongsToPcExpenses;

        return $this->baseSucceed($respond_data = $info, $message = '修改成功');
    }

    /**
     * Remove the specified resource from storage.
     * 删除
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($id);
        $this->pcClaimExpensesFromTab->destroy($id);
        return $this->baseSucceed($message = '修改成功');
    }
}
