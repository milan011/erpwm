<?php
namespace App\Repositories\TaxAuthorities;

use App\Repositories\BaseInterface\Repository;
use App\Repositories\TaxAuthorities\TaxAuthoritiesRepositoryInterface;
use App\TaxAuthorities;
use App\Taxauthrates;
use App\TaxCategories;
use App\Taxprovinces;
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

class TaxAuthoritiesRepository implements TaxAuthoritiesRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['taxid', 'description', 'taxglcode', 'purchtaxglaccount', 'bank', 'bankacctype', 'bankacc', 'bankswift'];

    // 根据ID获得信息
    public function find($id)
    {
        return TaxAuthorities::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new TaxAuthorities(); // 返回的是一个Order实例,两种方法均可
        $query = $query->with('belongsToChartMasterByTaxglcode', 'belongsToChartMasterByPurchtaxglaccount');
        $query->orderBy('taxid', 'DESC');
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
        // dd($requestData->all());
        DB::beginTransaction();
        try {

            $tax_authorities = new TaxAuthorities(); //税目
            $tax_authrates   = new Taxauthrates(); //税率

            $tax_categories = TaxCategories::get(); ////税种
            $tax_provinces  = Taxprovinces::get(); //纳税区域

            // dd($tax_provinces);
            // dd($tax_authorities->get());
            $input = array_replace($requestData->all());
            $tax_authorities->fill($input);
            $tax_authorities = $tax_authorities->create($input);
            // dd($tax_authorities);
            $authrates_arr = []; //需要插入税率表的数据

            foreach ($tax_provinces as $key => $value) {
                foreach ($tax_categories as $k => $v) {
                    $authrates_arr[$key][$k]['taxauthority']        = $tax_authorities->taxid;
                    $authrates_arr[$key][$k]['dispatchtaxprovince'] = $value->taxprovinceid;
                    $authrates_arr[$key][$k]['taxcatid']            = $v->taxcatid;
                    $authrates_arr[$key][$k]['taxrate']             = 0;
                }
            }

            $authrates_arr = collect($authrates_arr);
            $authrates_arr = $authrates_arr->collapse();
            // dd($authrates_arr);

            foreach ($authrates_arr as $key => $value) {
                $tax_authrates->create($value);
            }

            DB::commit();
            return $tax_authorities;

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
        $info = TaxAuthorities::select($this->select_columns)->findorFail($id); //获取信息

        $info->description       = $requestData->description;
        $info->taxglcode         = $requestData->taxglcode;
        $info->purchtaxglaccount = $requestData->purchtaxglaccount;
        $info->bank              = !empty($requestData->bank) ? $requestData->bank : '';
        $info->bankacctype       = !empty($requestData->bankacctype) ? $requestData->bankacctype : '';
        $info->bankacc           = !empty($requestData->bankacc) ? $requestData->bankacc : '';
        $info->bankswift         = !empty($requestData->bankswift) ? $requestData->bankswift : '';
        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $tax_authrates = new Taxauthrates(); //税率
            $info          = TaxAuthorities::findorFail($id);
            $info->delete(); //删除税目

            $tax_authrates->where('taxauthority', $id)->delete(); //删除关联的税目

            DB::commit();
            return $info;

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return false;
        }
    }

    //名称是否重复
    public function isRepeat($description)
    {
        return TaxAuthorities::where('description', $description)->first();
    }
}
