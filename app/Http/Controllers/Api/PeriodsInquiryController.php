<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\periodsInquiry;
//use App\Http\Resources\PeriodsInquiry\PeriodsInquiryResource;
//use App\Http\Resources\PeriodsInquiry\PeriodsInquiryResourceCollection;
use App\Repositories\PeriodsInquiry\PeriodsInquiryRepositoryInterface;
use Illuminate\Http\Request;

class PeriodsInquiryController extends Controller
{

    public function __construct(

        PeriodsInquiryRepositoryInterface $periodsInquiry
    ) {
        $this->periodsInquiry = $periodsInquiry;
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query_list = jsonToArray($request); //获取搜索信息

        $periodsInquirys = $this->periodsInquiry->getList($query_list);

        return $periodsInquirys;
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

        if ($this->periodsInquiry->isRepeat($request->new_telephone)) {
            return $this->baseFailed($message = '入网号码已存在');
        }

        $info = $this->periodsInquiry->create($request);
        $info->hasOnePackage;
        $info->belongsToCreater;

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
        $info = $this->periodsInquiry->find($id);
        $info->belongsToCreater;

        return new PeriodsInquiryResource($info);
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
        $update_info = $this->periodsInquiry->isRepeat($request->periodsInquiryname);

        if ($update_info && ($update_info->taxprovinceid != $id)) {
            return $this->baseFailed($message = '您修改后的信息与现有冲突');
        }

        $info = $this->periodsInquiry->update($request, $id);
        $info->hasOnePackage;

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
        $this->periodsInquiry->destroy($id);
        return $this->baseSucceed($message = '修改成功');
    }
}
