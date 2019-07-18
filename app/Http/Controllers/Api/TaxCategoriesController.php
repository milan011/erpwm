<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
// use App\Http\Resources\TaxCategries\TaxCategriesResourceCollection;
use App\Repositories\TaxCategories\TaxCategoriesRepositoryInterface;
// use App\Http\Resources\TaxCategories\TaxCategoriesResource;
use App\TaxCategories;
use Illuminate\Http\Request;

class TaxCategoriesController extends Controller
{
    protected $TaxCategories;

    public function __construct(
        TaxCategoriesRepositoryInterface $TaxCategories
    ) {
        $this->TaxCategories = $TaxCategories;
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表(分页)
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query_list = jsonToArray($request); //获取搜索信息

        $TaxCategoriess = TaxCategories::where('status', '1')->orderBy('taxcatid', 'DESC')->paginate(10);

        // dd($TaxCategoriess);

        // return new TaxCategoriesResource($TaxCategoriess);
        return $TaxCategoriess;
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

        if ($this->TaxCategories->isRepeat($request->taxcatname)) {
            return $this->baseFailed($message = '税目名称重复');
        }
        // dd('不重复');
        // $this->isRepeat($request->taxcatname);

        $new_taxCategories = $this->TaxCategories->create($request);

        if ($new_taxCategories) {
            //添加成功
            return $this->baseSucceed($new_taxCategories, '添加成功');
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
        $info = $this->TaxCategries->find($id);
        $info->belongsToCreater;

        return new TaxCategriesResource($info);
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
        $update_info = $this->TaxCategories->isRepeat($request->taxcatname);
        if ($update_info && ($update_info->taxcatid != $id)) {
            return $this->baseFailed($message = '您修改后的税目信息与现有税目冲突');
        }

        $info = $this->TaxCategories->update($request, $id);

        return $this->baseSucceed($info, '修改成功');
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
        $this->TaxCategories->destroy($id);
        return $this->baseSucceed($message = '修改成功');
    }
}
