<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
// use App\Http\Resources\TaxAuthorities\TaxAuthoritiesResource;
// use App\Http\Resources\TaxAuthorities\TaxAuthoritiesResourceCollection;
use App\Repositories\TaxAuthorities\TaxAuthoritiesRepositoryInterface;
use Illuminate\Http\Request;

class TaxAuthoritiesController extends Controller
{
    protected $taxAuthorities;

    public function __construct(

        TaxAuthoritiesRepositoryInterface $taxAuthorities
    ) {
        $this->taxAuthorities = $taxAuthorities;
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());
        $query_list = jsonToArray($request); //获取搜索信息

        $taxAuthoritiess = $this->taxAuthorities->getList($query_list);

        return $taxAuthoritiess;
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

        if ($this->taxAuthorities->isRepeat($request->new_telephone)) {
            return $this->baseFailed($message = '入网号码已存在');
        }

        $info = $this->taxAuthorities->create($request);
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
        $info = $this->taxAuthorities->find($id);
        $info->belongsToCreater;

        return new TaxAuthoritiesResource($info);
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

        $info = $this->taxAuthorities->update($request, $id);
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
        $this->taxAuthorities->destroy($id);
        return $this->baseSucceed($message = '修改成功');
    }
}
