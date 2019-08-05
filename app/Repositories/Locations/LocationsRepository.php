<?php
namespace App\Repositories\Locations;

use App\Locations;
use App\Locstock;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\Locations\LocationsRepositoryInterface;
use App\StockMaster;
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

class LocationsRepository implements LocationsRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id', 'locationname', 'deladd1', 'deladd2', 'deladd3', 'deladd4', 'deladd5', 'deladd6', 'tel', 'fax', 'email', 'contact', 'taxprovinceid', 'cashsalecustomer', 'cashsalebranch', 'managed', 'internalrequest', 'status'];

    // 根据ID获得信息
    public function find($id)
    {
        return Locations::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new Locations(); // 返回的是一个Order实例,两种方法均可
        $query = $query->with('belongsToTaxprovinces', 'belongsToDebtorsMaster', 'belongsToCustbranch')
            ->where('status', '1')
            ->orderBy('id', 'DESC');
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

            $location = new Locations(); //仓库
            $locstock = new Locstock(); //物料-仓库关联

            $stockMaster = StockMaster::where('status', '1')->get(); //物料

            $input = array_replace($requestData->all());
            $location->fill($input);
            $location = $location->create($input);

            $locstock_arr = []; //需要插入税率表的数据

            foreach ($stockMaster as $k => $v) {
                $locstock_arr[$k]['loccode'] = $location->id;
                $locstock_arr[$k]['stockid'] = $v->id;
            }

            // dd($locstock_arr);

            foreach ($locstock_arr as $key => $value) {
                $locstock->create($value);
            }

            DB::commit();
            return $location;

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
        $info = Locations::select($this->select_columns)->findorFail($id); //获取信息

        $info->locationname = $requestData->locationname;
        $info->deladd1      = $requestData->deladd1;
        $info->tel          = $requestData->tel;
        if (!empty($requestData->fax)) {
            $info->fax = $requestData->fax;
        }
        $info->email            = $requestData->email;
        $info->contact          = $requestData->contact;
        $info->taxprovinceid    = $requestData->taxprovinceid;
        $info->cashsalebranch   = $requestData->cashsalebranch;
        $info->cashsalecustomer = $requestData->cashsalecustomer;
        $info->internalrequest  = $requestData->internalrequest;

        // dd($info);
        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info = Locations::findorFail($id);

            $numUsed               = [];
            $numUsed['saleOrder']  = $info->hasManySalesOrders()->count();
            $numUsed['stockMove']  = $info->hasManyStockMoves()->count();
            $numUsed['Locstock']   = $info->hasManyLocstockHasQuantity()->count();
            $numUsed['User']       = $info->hasManyUser()->count();
            $numUsed['Bom']        = $info->hasManyBom()->count();
            $numUsed['WorkOrders'] = $info->hasManyWorkOrders()->count();
            $numUsed['Custbranch'] = $info->hasManyCustbranch()->count();
            $numUsed['PurchOrder'] = $info->hasManyPurchOrders()->count();

            // dd($numUsed);
            $isUsed = count(array_filter($numUsed));

            // dd($isUsed);
            if ($isUsed != 0) {
                //仓库正在被使用
                return false;
            }

            $info->status = '0'; //删除仓库
            $info->save();

            $info->hasManyLocstock()->delete();

            DB::commit();
            return $info;

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return false;
        }
    }

    //名称是否重复
    public function isRepeat($locationname)
    {
        return Locations::where('locationname', $locationname)->where('status', '1')->first();
    }
}
