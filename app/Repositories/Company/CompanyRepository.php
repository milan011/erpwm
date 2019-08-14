<?php
namespace App\Repositories\Company;

use App\Company;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\Company\CompanyRepositoryInterface;
use Auth;
use Datatables;
use DB;
use Debugbar;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PHPZen\LaravelRbac\Traits\Rbac;
use Planbon;
use Session;

class CompanyRepository implements CompanyRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id', 'coycode', 'coyname', 'gstno', 'companynumber', 'regoffice1', 'regoffice2', 'regoffice3', 'regoffice4', 'regoffice5', 'regoffice6', 'telephone', 'fax', 'email', 'currencydefault', 'debtorsact', 'pytdiscountact', 'creditorsact', 'payrollact', 'grnact', 'exchangediffact', 'purchasesexchangediffact', 'retainedearnings', 'gllink_debtors', 'gllink_creditors', 'gllink_stock', 'freightact'];

    // 根据ID获得信息
    public function find($id)
    {
        return Company::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new Company(); // 返回的是一个Order实例,两种方法均可
        $query = $query->where('status', '1')->orderBy('id', 'DESC');
        // $query = $query->with('');
        if (empty($queryList['page'])) {
            //无分页,全部返还
            return $query->get();
        } else {
            return $query->paginate(10);
        }
    }

    // 创建信息
    public function create($requestData)
    {

        DB::beginTransaction();
        try {

            $example = new Company(); //税目

            $input = array_replace($requestData->all());
            $example->fill($input);
            $example = $example->create($input);

            DB::commit();
            return $example;

        } catch (\Exception $e) {
            throw $e;
            DB::rollBack();
            return false;
        }

    }

    // 信息更新
    public function update($requestData, $id)
    {
        // dd($requestData->all());
        $info = Company::select($this->select_columns)->findorFail($id); //获取信息

        $info->taxcatname = $requestData->taxcatname;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info         = Company::findorFail($id);
            $info->status = '0'; //删除税目
            $info->save();

            DB::commit();
            return $info;

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return false;
        }
    }

    //名称是否重复
    public function isRepeat($taxcatname)
    {
        return Company::where('taxcatname', $taxcatname)->where('status', '1')->first();
    }
}
