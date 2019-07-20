<?php
namespace App\Repositories\TaxGroupTaxes;

use App\Repositories\BaseInterface\Repository;
use App\Repositories\TaxGroupTaxes\TaxGroupTaxesRepositoryInterface;
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

class TaxGroupTaxesRepository implements TaxGroupTaxesRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id', 'taxgroupid', 'taxauthid', 'calculationorder', 'taxontax'];

    // 根据ID获得信息
    public function find($id)
    {
        return TaxGroupTaxes::select($this->select_columns)->with('hasManyTaxGroupTaxes')
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new TaxGroupTaxes(); // 返回的是一个Order实例,两种方法均可
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

            $tax_group_taxs = new TaxGroupTaxes(); //税目

            // dd($tax_provinces);
            // dd($tax_authorities->get());

            $input = array_replace($requestData->all());
            // dd($input);
            $input['taxauthid'] = $input['taxid'];
            $tax_group_taxs->fill($input);
            $tax_group_taxs = $tax_group_taxs->create($input);

            DB::commit();
            return $tax_group_taxs;

        } catch (\Exception $e) {
            throw $e;
            DB::rollBack();
            return false;
        }

    }

    // 信息更新
    public function update($requestData, $id)
    {
        $info = TaxGroupTaxes::findorFail($id);

        $info->calculationorder = $requestData->calculationorder;
        $info->taxontax         = $requestData->taxontax;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info = TaxGroupTaxes::findorFail($id);

            $info->delete(); //

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
        return TaxGroupTaxes::where('taxgroupdescription', $taxgroupdescription)->first();
    }
}
