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
        $info    = TaxCategories::select($this->select_columns)->findorFail($id); //获取信息
        $manager = Manager::findOrFail($requestData['manage_id']); //获得客户经理信息
        $package = Package::findOrFail($requestData['package_id']); //获得客户经理信息
        // dd($manager);
        // 处理副卡信息
        // dd($requestData->all());
        $side_list_info      = [];
        $side_number_arr     = [];
        $side_uim_number_arr = [];
        $side_list           = [];
        $side_number         = '';
        $side_uim_number     = '';

        // dd(array_filter($requestData['side_numbers']));
        // dd(array_filter($requestData['side_numbers']));
        // dd(empty(array_filter($requestData['side_numbers'])));
        if (!empty(array_filter($requestData['side_numbers']))) {
            foreach ($requestData['side_numbers'] as $key => $value) {
                $side_list_info[$key]['side'] = $value['side_number'];
                $side_list_info[$key]['uim']  = $value['uim'];
            }
            // dd(array_filter($side_list_info));
            $side_list = a_array_unique($side_list_info);
            // dd($side_list);
            foreach ($side_list as $key => $value) {
                if ($value['side'] !== null) {
                    $side_number_arr[]     = $value['side'];
                    $side_uim_number_arr[] = $value['uim'];
                }
            }
            // dd($side_number_arr);
            $side_number     = implode("|", $side_number_arr);
            $side_uim_number = implode("|", $side_uim_number_arr);
        }

        $side_uim_number_num = count(array_unique(array_filter($side_uim_number_arr)));

        $info->name                = $requestData->name;
        $info->user_telephone      = $requestData->user_telephone;
        $info->manage_name         = $manager->name;
        $info->manage_telephone    = $manager->telephone;
        $info->manage_id           = $manager->id;
        $info->package_id          = $package->id;
        $info->package_month       = $package->month_nums;
        $info->project_name        = $requestData->project_name;
        $info->side_number_num     = count($side_number_arr);
        $info->uim_number          = $requestData->uim_number;
        $info->collections         = $requestData->collections;
        $info->side_number         = $side_number;
        $info->side_uim_number     = $side_uim_number;
        $info->remark              = $requestData->remark;
        $info->side_uim_number_num = $side_uim_number_num;
        $info->collections_type    = $requestData->collections_type;
        $info->netin               = $requestData->netin_year . '-' . $requestData->netin_month;
        $info->old_bind            = ($requestData->old_bind) ? '1' : '0';
        $info->is_jituan           = ($requestData->is_jituan) ? '1' : '0';

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        try {
            $info         = TaxCategories::findorFail($id);
            $info->status = '0';
            $info->save();

            return $info;

        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }
    }

    //名称是否重复
    public function isRepeat($taxcatname)
    {
        return TaxCategories::where('taxcatname', $taxcatname)->first();
    }
}
