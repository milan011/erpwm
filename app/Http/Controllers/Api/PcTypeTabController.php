<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
// use App\Http\Resources\PcTypeTab\PcTypeTabResource;
// use App\Http\Resources\PcTypeTab\PcTypeTabResourceCollection;
use App\PcExpensesTypeTab;
use App\Repositories\PcTypeTab\PcTypeTabRepositoryInterface;
use DB;
use Illuminate\Http\Request;

class PcTypeTabController extends Controller
{
    protected $pcTypeTab;

    public function __construct(

        PcTypeTabRepositoryInterface $pcTypeTab
    ) {
        $this->pcTypeTab = $pcTypeTab;
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query_list = jsonToArray($request); //获取搜索信息

        $pcTypeTabs = $this->pcTypeTab->getList($query_list);

        return $pcTypeTabs;
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

        if ($this->pcTypeTab->isRepeat($request->typetabdescription)) {
            return $this->baseFailed($message = '标签已存在');
        }

        $info = $this->pcTypeTab->create($request);
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
        $info = $this->pcTypeTab->find($id);
        $info->belongsToCreater;

        return new PcTypeTabResource($info);
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
        $update_info = $this->pcTypeTab->isRepeat($request->typetabdescription);

        if ($update_info && ($update_info->id != $id)) {
            return $this->baseFailed($message = '标签已存在');
        }

        $info = $this->pcTypeTab->update($request, $id);
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
        $this->pcTypeTab->destroy($id);
        return $this->baseSucceed($message = '修改成功');
    }

    //获取费用种类
    public function getTypePcExpenses($id)
    {
        // dd($id);
        $pc_expenses = PcExpensesTypeTab::where('typetabcode', $id)->get();
        // dd($pc_expenses);

        $curr_list = [];

        if (!empty($pc_expenses)) {
            foreach ($pc_expenses as $key => $value) {
                $curr_list[] = (int) $value->codeexpense;
            }
        }

        // dd($curr_list);
        return response([
            'data' => $curr_list,
        ]);

    }

    //分配费用种类
    public function giveTypePcExpenses($id, Request $request)
    {
        // p($id);
        // dd($request->list);
        if (!empty($request->list)) {
            //有费用种类
            $data_list = [];
            foreach ($request->list as $key => $value) {
                $data_list[$key]['codeexpense'] = (string) $value;
                $data_list[$key]['typetabcode'] = $id;
            }
            // dd($data_list);
            DB::beginTransaction();
            try {

                //删除现在关联
                DB::table('pctabexpenses')->where('typetabcode', $id)->delete();

                $pc = new PcExpensesTypeTab();

                foreach ($data_list as $key => $value) {
                    $input = array_replace($value);
                    $pc->fill($input);
                    $pc = $pc->create($input);
                }

                DB::commit();
                return $pc;

            } catch (\Exception $e) {
                throw $e;
                DB::rollBack();
                return false;
            }
        } else {
            //没有选择费用种类
            DB::table('pctabexpenses')->where('typetabcode', $id)->delete();
        }
    }
}
