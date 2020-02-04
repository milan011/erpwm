<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
// use App\Http\Resources\FixedAssets\FixedAssetsResource;
// use App\Http\Resources\FixedAssets\FixedAssetsResourceCollection;
use App\Repositories\FixedAssets\FixedAssetsRepositoryInterface;
use Illuminate\Http\Request;

class FixedAssetsController extends Controller
{
    protected $fixedAssets;

    public function __construct(

        FixedAssetsRepositoryInterface $fixedAssets
    ) {
        $this->fixedAssets = $fixedAssets;
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query_list = jsonToArray($request); //获取搜索信息

        $fixedAssetss = $this->fixedAssets->getList($query_list);

        return $fixedAssetss;
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

        if ($this->fixedAssets->isRepeat($request->description)) {
            return $this->baseFailed($message = '数据已存在');
        }

        $info = $this->fixedAssets->create($request);
        $info->belongsToFixedAssetCategorie;
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
        $info = $this->fixedAssets->find($id);
        $info->belongsToCreater;

        return new FixedAssetsResource($info);
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
        $update_info = $this->fixedAssets->isRepeat($request->description);

        if ($update_info && ($update_info->assetid != $id)) {
            return $this->baseFailed($message = '数据已存在');
        }

        $info = $this->fixedAssets->update($request, $id);
        $info->belongsToFixedAssetCategorie;
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
        $info = $this->fixedAssets->destroy($id);

        if ($info['status']) {
            //删除成功
            return $this->baseSucceed($respond_data = $info, $message = '删除成功');
        } else {
            //删除失败
            return $this->baseFailed($message = $info['message']);
        }
    }
}
