<?php
namespace App\Repositories\DiscountMatrix;

use App\DiscountMatrix;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\DiscountMatrix\DiscountMatrixRepositoryInterface;
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

class DiscountMatrixRepository implements DiscountMatrixRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id', 'salestype', 'discountcategory', 'quantitybreak', 'discountrate', 'status'];

    // 根据ID获得信息
    public function find($id)
    {
        return DiscountMatrix::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new DiscountMatrix(); // 返回的是一个Order实例,两种方法均可
        $query = $query->where('status', '1')->orderBy('id', 'DESC')->with('belongsToSaleType');
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

            $example = new DiscountMatrix(); //税目

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
        $info = DiscountMatrix::select($this->select_columns)->findorFail($id); //获取信息

        $info->salestype        = $requestData->salestype;
        $info->discountcategory = $requestData->discountcategory;
        $info->quantitybreak    = $requestData->quantitybreak;
        $info->discountrate     = $requestData->discountrate;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info         = DiscountMatrix::findorFail($id);
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
        return DiscountMatrix::where('taxcatname', $taxcatname)->where('status', '1')->first();
    }
}
