<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Repositories\Company\CompanyRepositoryInterface;
// use App\Http\Resources\FixedAssetCategorie\FixedAssetCategorieResource;
// use App\Http\Resources\FixedAssetCategorie\FixedAssetCategorieResourceCollection;
use App\Repositories\FixedAssetCategorie\FixedAssetCategorieRepositoryInterface;
use DB;
use Illuminate\Http\Request;

class FixedAssetCategorieController extends Controller
{
    protected $fixedAssetCategorie;
    protected $company;

    public function __construct(

        FixedAssetCategorieRepositoryInterface $fixedAssetCategorie,
        CompanyRepositoryInterface $company
    ) {
        $this->fixedAssetCategorie = $fixedAssetCategorie;
        $this->company             = $company;
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query_list = jsonToArray($request); //获取搜索信息

        $fixedAssetCategories = $this->fixedAssetCategorie->getList($query_list);

        return $fixedAssetCategories;
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

        $checked = $this->checkAccounts($request);
        // dd($checked);
        if (!$checked['status']) {
            return $this->baseFailed($message = $checked['message']);
        }

        if ($this->fixedAssetCategorie->isRepeat($request->categorydescription)) {
            return $this->baseFailed($message = '种类名称重复');
        }

        $info = $this->fixedAssetCategorie->create($request);
        $info->belongsToChartMasterWithDepnact;
        $info->belongsToChartMasterWithAccumdepnact;
        $info->belongsToChartMasterWithDisposalact;
        $info->belongsToChartMasterWithCostact;

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
        $info = $this->fixedAssetCategorie->find($id);
        $info->belongsToCreater;

        return new FixedAssetCategorieResource($info);
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
        $checked = $this->checkAccounts($request);
        // dd($checked);
        if (!$checked['status']) {
            return $this->baseFailed($message = $checked['message']);
        }

        $update_info = $this->fixedAssetCategorie->isRepeat($request->categorydescription);

        if ($update_info && ($update_info->id != $id)) {
            return $this->baseFailed($message = '种类名称重复');
        }

        $info = $this->fixedAssetCategorie->update($request, $id);
        $info->belongsToChartMasterWithDepnact;
        $info->belongsToChartMasterWithAccumdepnact;
        $info->belongsToChartMasterWithDisposalact;
        $info->belongsToChartMasterWithCostact;

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
        $info = $this->fixedAssetCategorie->destroy($id);
        if ($info) {
            //添加成功
            return $this->baseSucceed($respond_data = $info, $message = '删除成功');
        } else {
            //添加失败
            return $this->baseFailed($message = '因为此种类已经被其他固定资产使用因此无法删除');
        }
    }

    /**
     * Remove the specified resource from storage.
     * 判断科目是否符合条件
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    private function checkAccounts($request)
    {
        $companyInfo = $this->company->find(1);
        /*dd($companyInfo);
        dd($request->all());*/
        $data = [
            'status'  => true,
            'message' => '',
        ];
        /*检查所选costact及accumdepnact与公司配置是否冲突*/
        if (($request->costact == $companyInfo->debtorsact) ||
            ($request->costact == $companyInfo->creditorsact) ||
            ($request->costact == $companyInfo->grnact) ||
            ($request->accumdepnact == $companyInfo->debtorsact) ||
            ($request->accumdepnact == $companyInfo->creditorsact) ||
            ($request->accumdepnact == $companyInfo->grnact)) {
            // dd('nono');
            /*$message = '所选的要过账成本或者累计折旧的科目，不可以是应收统制账户，应付统制账户或者已收货暂估账户';*/
            $data['status']  = false;
            $data['message'] = '所选的要过账成本或者累计折旧的科目，不可以是应收统制账户，应付统制账户或者已收货暂估账户';
        }

        /*检查已分配的银行账户*/
        $bankAccounts = DB::select("SELECT bankaccounts.accountcode FROM bankaccounts INNER JOIN chartmaster ON bankaccounts.accountcode=chartmaster.id");
        // dd(lastSql());
        // dd($bankAccounts[0]->accountcode);
        $bankAccountsList = [];

        foreach ($bankAccounts as $key => $value) {
            $bankAccountsList[] = $value->accountcode;
        }

        // dd($bankAccountsList);

        if (in_array($request->costact, $bankAccountsList)) {
            // $message = '所选的资产成本账户是银行账户-您不可以将非银行账户的科目过账到受保护的银行账户。选择另外的资产负债表账户来处理资产成本';
            $data['status']  = false;
            $data['message'] = '所选的资产成本账户是银行账户-您不可以将非银行账户的科目过账到受保护的银行账户。选择另外的资产负债表账户来处理资产成本';
        }

        if (in_array($request->accumdepnact, $bankAccountsList)) {
            // $message = '所选的累计折旧账户是银行账户-银行账户为受保护账户，不可以从其他账户过账。选择其他的资产账户来处理资产累计折旧';
            $data['status']  = false;
            $data['message'] = '所选的累计折旧账户是银行账户-银行账户为受保护账户，不可以从其他账户过账。选择其他的资产账户来处理资产累计折旧';
        }

        return $data;
    }
}
