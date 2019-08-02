<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
// use App\Http\Resources\StockCategory\StockCategoryResource;
// use App\Http\Resources\StockCategory\StockCategoryResourceCollection;
use App\Repositories\StockCategory\StockCategoryRepositoryInterface;
use Illuminate\Http\Request;

class StockCategoryController extends Controller
{
    protected $stockCategory;

    public function __construct(

        StockCategoryRepositoryInterface $stockCategory
    ) {
        $this->stockCategory = $stockCategory;
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query_list = jsonToArray($request); //获取搜索信息

        $stockCategorys = $this->stockCategory->getList($query_list);

        return $stockCategorys;
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

        if ($this->stockCategory->isRepeat($request->categorydescription)) {
            return $this->baseFailed($message = '已存在该科目');
        }

        $info = $this->stockCategory->create($request);
        $info->belongsToTaxCategories;
        $info->belongsToChartMasterWithStockact;
        $info->belongsToChartMasterWithWipact;
        $info->belongsToChartMasterWithAdjglact;
        $info->belongsToChartMasterWithIssueglact;
        $info->belongsToChartMasterWithPurchpricevaract;
        $info->belongsToChartMasterWithMaterialuseagevarac;
        $info->hasManyStockMaster;
        $info->hasManyStockCatProperties;

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
        $info = $this->stockCategory->find($id);
        $info->belongsToCreater;

        return new StockCategoryResource($info);
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
        $update_info = $this->stockCategory->isRepeat($request->categorydescription);

        if ($update_info && ($update_info->id != $id)) {
            return $this->baseFailed($message = '已存在该科目');
        }

        $info = $this->stockCategory->update($request, $id);
        $info->belongsToTaxCategories;
        $info->belongsToChartMasterWithStockact;
        $info->belongsToChartMasterWithWipact;
        $info->belongsToChartMasterWithAdjglact;
        $info->belongsToChartMasterWithIssueglact;
        $info->belongsToChartMasterWithPurchpricevaract;
        $info->belongsToChartMasterWithMaterialuseagevarac;
        $info->hasManyStockMaster;
        $info->hasManyStockCatProperties;

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
        $info = $this->stockCategory->destroy($id);
        if ($info) {
            return $this->baseSucceed($message = '修改成功');
        } else {
            return $this->baseFailed($message = '该物料组正在被使用,无法删除');
        }

    }
}
