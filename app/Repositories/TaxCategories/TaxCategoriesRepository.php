<?php
namespace App\Repositories\TaxCategories;

use App\Repositories\BaseInterface\Repository;
use App\Repositories\TaxCategories\TaxCategoriesRepositoryInterface;
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

class TaxCategoriesRepository implements TaxCategoriesRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['taxcatid', 'taxcatname'];

    // 根据ID获得信息
    public function find($id)
    {
        return TaxCategories::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getAllInfos($queryList)
    {

    }

    // 创建信息
    public function create($requestData)
    {

        DB::beginTransaction();
        try {

            $tax_categories = new TaxCategories(); //税目
            $tax_authrates  = new Taxauthrates(); //税率

            $tax_authorities = TaxAuthorities::get(); ////税种
            $tax_provinces   = Taxprovinces::get(); //纳税区域

            // dd($tax_provinces);
            // dd($tax_authorities->get());
            $input = array_replace($requestData->all());
            $tax_categories->fill($input);
            $tax_categories = $tax_categories->create($input);
            // dd($tax_categories);
            $authrates_arr = []; //需要插入税率表的数据

            foreach ($tax_provinces as $key => $value) {
                foreach ($tax_authorities as $k => $v) {
                    $authrates_arr[$key][$k]['taxauthority']        = $v->taxid;
                    $authrates_arr[$key][$k]['dispatchtaxprovince'] = $value->taxprovinceid;
                    $authrates_arr[$key][$k]['taxcatid']            = $tax_categories->taxcatid;
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
            return $tax_categories;

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
        $info = TaxCategories::select($this->select_columns)->findorFail($id); //获取信息

        $info->taxcatname = $requestData->taxcatname;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $tax_authrates = new Taxauthrates(); //税率
            $info          = TaxCategories::findorFail($id);
            $info->status  = '0'; //删除税目
            $info->save();

            $tax_authrates->where('taxcatid', $id)->delete(); //删除关联的税目

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
        return TaxCategories::where('taxcatname', $taxcatname)->where('status', '1')->first();
    }
}
