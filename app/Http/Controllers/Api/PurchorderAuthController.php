<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
// use App\Http\Resources\PurchorderAuth\PurchorderAuthResource;
// use App\Http\Resources\PurchorderAuth\PurchorderAuthResourceCollection;
use App\Repositories\PurchorderAuth\PurchorderAuthRepositoryInterface;
use Illuminate\Http\Request;

class PurchorderAuthController extends Controller
{
    protected $purchorderAuth;

    public function __construct(

        PurchorderAuthRepositoryInterface $purchorderAuth
    ) {
        $this->purchorderAuth = $purchorderAuth;
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query_list = jsonToArray($request); //获取搜索信息

        $purchorderAuths = $this->purchorderAuth->getList($query_list);

        return $purchorderAuths;
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

        if ($this->purchorderAuth->isRepeat($request->userid, $request->currabrev)) {
            return $this->baseFailed($message = '数据重复');
        }

        $info = $this->purchorderAuth->create($request);
        $info->belongsToUser;
        $info->belongsToCurrencies;

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
        $info = $this->purchorderAuth->find($id);
        $info->belongsToCreater;

        return new PurchorderAuthResource($info);
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
        $update_info = $this->purchorderAuth->isRepeat($request->userid, $request->currabrev);

        if ($update_info && ($update_info->id != $id)) {
            return $this->baseFailed($message = '您修改后的信息与现有冲突');
        }

        $info = $this->purchorderAuth->update($request, $id);
        $info->belongsToUser;
        $info->belongsToCurrencies;

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
        $this->purchorderAuth->destroy($id);
        return $this->baseSucceed($message = '修改成功');
    }
}
