<?php
namespace App\Repositories\TaxProvinces;

use App\Repositories\BaseInterface\Repository;
//use App\Area;
use App\Repositories\TaxProvinces\TaxProvincesRepositoryInterface;
use App\TaxAuthorities;
use App\Taxauthrates;
use App\TaxCategories;
use App\Taxprovinces;
use Auth;
use Carbon;
use Datatables;
use DB;
use Debugbar;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PHPZen\LaravelRbac\Traits\Rbac;
use Session;

class TaxProvincesRepository implements TaxProvincesRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['taxprovinceid', 'taxprovincename'];

    // 根据ID获得车源信息
    public function find($id)
    {
        return TaxProvinces::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        // dd($queryList);
        // $query = Order::query();  // 返回的是一个 QueryBuilder 实例
        $query = new TaxProvinces(); // 返回的是一个Order实例,两种方法均可

        if (empty($queryList)) {
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

            $tax_provinces = new TaxProvinces(); //纳税区域
            $tax_authrates = new Taxauthrates(); //税率

            $tax_authorities = TaxAuthorities::get(); //税种
            $tax_categories  = TaxCategories::where('status', '1')->get(); //税目

            $input = array_replace($requestData->all());
            $tax_provinces->fill($input);
            $tax_provinces = $tax_provinces->create($input);
            // dd($tax_provinces);
            $authrates_arr = []; //需要插入税率表的数据

            foreach ($tax_categories as $key => $value) {
                foreach ($tax_authorities as $k => $v) {
                    $authrates_arr[$key][$k]['taxauthority']        = $v->taxid;
                    $authrates_arr[$key][$k]['dispatchtaxprovince'] = $tax_provinces->taxprovinceid;
                    $authrates_arr[$key][$k]['taxcatid']            = $value->taxcatid;
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
            return $tax_provinces;

        } catch (\Exception $e) {
            throw $e;
            DB::rollBack();
            return false;
        }
    }

    // 信息更新
    public function update($requestData, $id)
    {

        $info = TaxProvinces::select($this->select_columns)->findorFail($id); //获取信息

        $info->taxprovincename = $requestData->taxprovincename;
        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $tax_authrates = new Taxauthrates(); //税率
            $info          = TaxProvinces::findorFail($id);
            $info->delete();

            $tax_authrates->where('dispatchtaxprovince', $id)->delete(); //删除关联的税目

            DB::commit();
            return $info;

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return false;
        }
    }

    //判断电话号码是否重复
    public function isRepeat($provinces_name)
    {

        $info = TaxProvinces::where('taxprovincename', $provinces_name)
            ->first();
        /*dd(lastSql());
        dd($info);*/
        return $info;

    }
}
