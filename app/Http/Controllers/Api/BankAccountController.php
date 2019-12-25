<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
// use App\Http\Resources\BankAccount\BankAccountResource;
// use App\Http\Resources\BankAccount\BankAccountResourceCollection;
use App\Repositories\BankAccount\BankAccountRepositoryInterface;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    protected $bankAccount;

    public function __construct(

        BankAccountRepositoryInterface $bankAccount
    ) {
        $this->bankAccount = $bankAccount;
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query_list = jsonToArray($request); //获取搜索信息

        $bankAccounts = $this->bankAccount->getList($query_list);

        return $bankAccounts;
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

        // dd($request->all());

        if ($this->bankAccount->isRepeat($request->bankaccountname, $request->bankaccountcode, $request->accountcode)) {
            return $this->baseFailed($message = '该会计科目银行账户和户名重复');
        }

        $info = $this->bankAccount->create($request);
        $info->belongsToChartMaster;

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
        $info = $this->bankAccount->find($id);
        $info->belongsToCreater;

        return new BankAccountResource($info);
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

        $update_info = $this->bankAccount->isRepeat($request->bankaccountname, $request->bankaccountcode, $request->accountcode);

        if ($update_info && ($update_info->id != $id)) {
            return $this->baseFailed($message = '该会计科目银行账户和户名重复');
        }

        $info = $this->bankAccount->update($request, $id);
        $info->belongsToChartMaster;

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
        $info = $this->bankAccount->destroy($id);

        if ($info['status']) {
            //删除成功
            return $this->baseSucceed($respond_data = $info, $message = '删除成功');
        } else {
            //删除失败
            return $this->baseFailed($message = $info['message']);
        }
    }
}
