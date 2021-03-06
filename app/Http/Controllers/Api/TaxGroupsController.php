<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
// use App\Http\Resources\TaxGroups\TaxGroupsResource;
// use App\Http\Resources\TaxGroups\TaxGroupsResourceCollection;
use App\Repositories\TaxAuthorities\TaxAuthoritiesRepositoryInterface;
use App\Repositories\TaxGroups\TaxGroupsRepositoryInterface;
use App\Repositories\TaxGroupTaxes\TaxGroupTaxesRepositoryInterface;
use Illuminate\Http\Request;

class TaxGroupsController extends Controller
{
    protected $taxGroups;
    protected $taxAuthorities;
    protected $TaxGroupTaxes;

    public function __construct(

        TaxGroupsRepositoryInterface $taxGroups,
        TaxGroupTaxesRepositoryInterface $TaxGroupTaxes,
        TaxAuthoritiesRepositoryInterface $taxAuthorities
    ) {
        $this->taxGroups      = $taxGroups;
        $this->taxAuthorities = $taxAuthorities;
        $this->TaxGroupTaxes  = $TaxGroupTaxes;
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query_list = jsonToArray($request); //获取搜索信息

        $taxGroupss = $this->taxGroups->getList($query_list);

        return $taxGroupss;
    }

    /**
     * Display the specified resource.
     * 根据税目组id获取分配税种信息
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function taxGroupAuthorities(Request $request)
    {
        $tax_group = $this->taxGroups->find($request->taxgroupid);
        // dd($tax_group['has_many_tax_group_taxes']);
        // dd($request->taxgroupid);
        // dd($tax_group);

        $tax_authorities = $this->taxAuthorities->getList(['withOutPage' => true]);
        // dd($tax_authorities);
        foreach ($tax_authorities as $key => $value) {
            // dd($value);
            $value->taxgroupid       = $tax_group->taxgroupid;
            $value->calculationorder = 1;
            $value->taxontax         = 0;
            $value->assigned         = false;
            $value->taxGroupTaxsId   = null;
            // $value->group_description = $tax_group['taxgroupdescription'];
            foreach ($tax_group->hasManyTaxGroupTaxes as $k => $v) {

                if ($value->taxid == $v['taxauthid']) {
                    /*p($value->taxid);
                    dd($v);*/
                    $value->calculationorder = $v['calculationorder'];
                    $value->taxontax         = $v['taxontax'];
                    $value->assigned         = true;
                    $value->taxGroupTaxsId   = $v['id'];
                }
            }
        }
        // dd($tax_authorities);
        return $tax_authorities;
    }

    /**
     * Display the specified resource.
     * 分配税目组税种
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setTaxGroupAuthorities(Request $request)
    {
        // dd($request->all());
        $taxGroupTaxes_obj = $this->TaxGroupTaxes;
        switch ($request->del) {
            case 'add':
                #新增分配
                $info    = $taxGroupTaxes_obj->create($request);
                $message = '税种分配成功';
                break;
            case 'update':
                # 修改已分配税种信息
                $info    = $taxGroupTaxes_obj->update($request, $request->taxGroupTaxsId);
                $message = '税种修改成功';
                break;
            case 'delete':
                # 删除已分配税种
                $info    = $taxGroupTaxes_obj->destroy($request->taxGroupTaxsId);
                $message = '税种删除成功';
                break;
            default:
                return $this->baseFailed($message = '内部错误');
                break;
        }

        return $this->baseSucceed($respond_data = $info, $message);
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

        if ($this->taxGroups->isRepeat($request->taxgroupdescription)) {
            return $this->baseFailed($message = '税收组已存在');
        }

        $info = $this->taxGroups->create($request);

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
        $info = $this->taxGroups->find($id);
        $info->belongsToCreater;

        return new TaxGroupsResource($info);
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
        $update_info = $this->taxGroups->isRepeat($request->taxgroupdescription);

        if ($update_info && ($update_info->taxgroupid != $id)) {
            return $this->baseFailed($message = '您修改后的税收组信息与现有税收组冲突');
        }

        $info = $this->taxGroups->update($request, $id);

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
        $info = $this->taxGroups->destroy($id);

        if ($info) {
            return $this->baseSucceed($message = '修改成功');
        } else {
            return $this->baseFailed($message = '该税收组被客户或供应商使用,不允许删除');
        }

    }
}
