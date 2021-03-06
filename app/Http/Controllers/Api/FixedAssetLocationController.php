<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
// use App\Http\Resources\FixedAssetLocation\FixedAssetLocationResource;
// use App\Http\Resources\FixedAssetLocation\FixedAssetLocationResourceCollection;
use App\Repositories\FixedAssetLocation\FixedAssetLocationRepositoryInterface;
use Illuminate\Http\Request;

class FixedAssetLocationController extends Controller
{
    protected $fixedAssetLocation;

    public function __construct(

        FixedAssetLocationRepositoryInterface $fixedAssetLocation
    ) {
        $this->fixedAssetLocation = $fixedAssetLocation;
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query_list = jsonToArray($request); //获取搜索信息

        $fixedAssetLocations = $this->fixedAssetLocation->getList($query_list);

        return $fixedAssetLocations;
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

        if ($this->fixedAssetLocation->isRepeat($request->locationdescription)) {
            return $this->baseFailed($message = '名称重复');
        }

        $info = $this->fixedAssetLocation->create($request);
        $info->belongsToFixedAssetLocation;

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
        $info = $this->fixedAssetLocation->find($id);
        $info->belongsToCreater;

        return new FixedAssetLocationResource($info);
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
        $update_info = $this->fixedAssetLocation->isRepeat($request->locationdescription);

        if ($update_info && ($update_info->id != $id)) {
            return $this->baseFailed($message = '名称重复');
        }

        $info = $this->fixedAssetLocation->update($request, $id);
        $info->belongsToFixedAssetLocation;

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
        $info = $this->fixedAssetLocation->destroy($id);
        if ($info) {
            //删除成功
            return $this->baseSucceed($respond_data = $info, $message = '删除成功');
        } else {
            //添加失败
            return $this->baseFailed($message = '此仓库有子仓库或固定资产因此无法删除');
        }
    }
}
