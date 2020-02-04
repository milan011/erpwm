<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
// use App\Http\Resources\AccountGroup\AccountGroupResource;
// use App\Http\Resources\AccountGroup\AccountGroupResourceCollection;
use App\Repositories\AccountGroup\AccountGroupRepositoryInterface;
use Illuminate\Http\Request;

class AccountGroupController extends Controller
{
    protected $accountGroup;

    public function __construct(

        AccountGroupRepositoryInterface $accountGroup
    ) {
        $this->accountGroup = $accountGroup;
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query_list = jsonToArray($request); //获取搜索信息

        $accountGroups = $this->accountGroup->getList($query_list);

        return $accountGroups;
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

        if ($this->accountGroup->isRepeat($request->groupname)) {
            return $this->baseFailed($message = '该名称已被使用');
        }

        $info = $this->accountGroup->create($request);
        $info->belongsToAccountSection;
        $info->belongsToAccountGroup;

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
        $info = $this->accountGroup->find($id);
        $info->belongsToCreater;

        return new AccountGroupResource($info);
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
        $update_info = $this->accountGroup->isRepeat($request->groupname);

        if ($update_info && ($update_info->id != $id)) {
            return $this->baseFailed($message = '该名称已被使用');
        }

        $info = $this->accountGroup->update($request, $id);
        $info->belongsToAccountSection;
        $info->belongsToAccountGroup;

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
        $info = $this->accountGroup->destroy($id);

        if ($info['status']) {
            //删除成功
            return $this->baseSucceed($respond_data = $info, $message = '删除成功');
        } else {
            //删除失败
            return $this->baseFailed($message = $info['message']);
        }
    }
}
