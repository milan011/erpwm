<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Repositories\ChartMaster\ChartMasterRepositoryInterface;
// use App\Http\Resources\ChartMaster\ChartMasterResource;
// use App\Http\Resources\ChartMaster\ChartMasterResourceCollection;

use Illuminate\Http\Request;

class ChartMasterController extends Controller
{
    protected $chartmaster;

    public function __construct(

        ChartMasterRepositoryInterface $chartmaster
    ) {
        $this->chartmaster = $chartmaster;
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query_list = jsonToArray($request); //获取搜索信息

        $chartmasters = $this->chartmaster->getList($query_list);

        return $chartmasters;
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

        if ($this->chartmaster->isRepeat($request->accountname)) {
            return $this->baseFailed($message = '科目组名称重复');
        }

        $info = $this->chartmaster->create($request);
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
        $info = $this->chartmaster->find($id);
        $info->belongsToCreater;

        return new ChartMasterResource($info);
    }

    /**
     * 获取科目组总帐预算信息
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getGLB($id)
    {
        // dd($id);

        $info = $this->chartmaster->getGLBInfo($id);

        if ($info) {
            //添加成功
            return $this->baseSucceed($respond_data = $info, $message = '获取预算信息');
        } else {
            //添加失败
            return $this->baseFailed($message = '内部错误');
        }
    }

    /**
     * 设置科目组总帐预算信息
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setGLB(Request $request)
    {
        $info = $this->chartmaster->setGLBInfo($request->allBudgets);

        if ($info) {
            //添加成功
            return $this->baseSucceed($respond_data = $info, $message = '更新预算信息成功');
        } else {
            //添加失败
            return $this->baseFailed($message = '内部错误');
        }
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
        $update_info = $this->chartmaster->isRepeat($request->accountname);

        if ($update_info && ($update_info->id != $id)) {
            return $this->baseFailed($message = '科目组名称重复');
        }

        $info = $this->chartmaster->update($request, $id);
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
        $info = $this->chartmaster->destroy($id);

        if ($info['status']) {
            //删除成功
            return $this->baseSucceed($respond_data = $info, $message = '删除成功');
        } else {
            //删除失败
            return $this->baseFailed($message = $info['message']);
        }

    }
}
