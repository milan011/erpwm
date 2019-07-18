<?php
namespace App\Repositories\TaxGroups;

use App\Repositories\BaseInterface\Repository;
use App\Repositories\TaxGroups\TaxGroupsRepositoryInterface;
use App\TaxGroups;
use App\TaxGroupTaxes;
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

class TaxGroupsRepository implements TaxGroupsRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['taxgroupid', 'taxgroupdescription'];

    // 根据ID获得信息
    public function find($id)
    {
        return TaxGroups::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new TaxGroups(); // 返回的是一个Order实例,两种方法均可
        $query = $query->with('hasManyTaxGroupTaxes')->orderBy('taxgroupid', 'DESC');
        if ($queryList['withOutPage']) {
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

            $tax_group = new TaxGroups(); //税目

            // dd($tax_provinces);
            // dd($tax_authorities->get());
            $input = array_replace($requestData->all());
            $tax_group->fill($input);
            $tax_group = $tax_group->create($input);

            DB::commit();
            return $tax_group;

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

        $info = TaxGroups::select($this->select_columns)->findorFail($id); //获取信息

        $info->taxgroupdescription = $requestData->taxgroupdescription;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $tax_authrates = new TaxGroupTaxes(); //税率
            $info          = TaxGroups::findorFail($id);
            /*p($info->hasManySuppliers->count());
            dd($info->hasManyCustbranch->count());
            dd($info);*/
            if (($info->hasManyCustbranch->count() != 0) || ($info->hasManySuppliers->count() != 0)) {
                return false;
            }
            $info->delete(); //删除税收组

            $tax_authrates->where('taxgroupid', $id)->delete(); //删除关联的税目

            DB::commit();
            return $info;

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return false;
        }
    }

    //名称是否重复
    public function isRepeat($taxgroupdescription)
    {
        return TaxGroups::where('taxgroupdescription', $taxgroupdescription)->first();
    }
}
