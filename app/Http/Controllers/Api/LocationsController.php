<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
// use App\Http\Resources\Locations\LocationsResource;
// use App\Http\Resources\Locations\LocationsResourceCollection;
use App\Repositories\Locations\LocationsRepositoryInterface;
use Illuminate\Http\Request;

class LocationsController extends Controller
{
    protected $locations;

    public function __construct(

        LocationsRepositoryInterface $locations
    ) {
        $this->locations = $locations;
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query_list = jsonToArray($request); //获取搜索信息

        $locationss = $this->locations->getList($query_list);

        return $locationss;
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

        if ($this->locations->isRepeat($request->locationname)) {
            return $this->baseFailed($message = '仓库名称重复');
        }

        $info = $this->locations->create($request);
        $info->belongsToTaxprovinces;
        $info->belongsToDebtorsMaster;
        $info->belongsToCustbranch;

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
        $info = $this->locations->find($id);
        $info->belongsToCreater;

        return new LocationsResource($info);
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
        $update_info = $this->locations->isRepeat($request->locationname);

        if ($update_info && ($update_info->id != $id)) {
            return $this->baseFailed($message = '您修改后的仓库名称与现有冲突');
        }

        $info = $this->locations->update($request, $id);
        $info->belongsToTaxprovinces;
        $info->belongsToDebtorsMaster;
        $info->belongsToCustbranch;

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
        $info = $this->locations->destroy($id);
        if ($info) {
            //删除成功
            return $this->baseSucceed($message = '修改成功');
        } else {
            //添加失败
            return $this->baseFailed($message = '该仓库可能被使用于销售订单,仓库调拨单,仓库仍有物料存在,已分配给用户,BOM使用,工作中心或工作单,分公司默认仓库');
        }

    }
}
