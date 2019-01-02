<?php
namespace App\Repositories\InventoryDetail;

use App\InventoryDetail;
use Session;
use Illuminate\Http\Request;
use Gate;
use Datatables;
use Carbon;
use PHPZen\LaravelRbac\Traits\Rbac;
use Auth;
use Illuminate\Support\Facades\Input;
use DB;
use Debugbar;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\InventoryDetail\InventoryDetailRepositoryInterface;

class InventoryDetailRepository implements InventoryDetailRepositoryInterface
{

    //默认查询数据
    protected $select_columns = ['id','goods_id', 'inventory_code', 'inventory_type', 'inventory_price', 'goods_nums', 'remark', 'creater_id', 'created_at', 'updated_at'];

    // 根据ID获得库存明细信息
    public function find($id)
    {
        return InventoryDetail::select($this->select_columns)
                              ->with('belongsToCreater')
                              ->with('belongsToGoods')
                              ->with('belongsToServiceDetail')
                              // ->with('belongsToServiecDetail')
                              ->findOrFail($id);
    }

    // 获得库存明细列表
    public function getAllInventoryDetail($queryList)
    {   
        $query = new InventoryDetail();       // 返回的是一个Plan实例,两种方法均可
        // dd($request->all());
        $query = $query->addCondition($queryList); //根据条件组合语句

        $query = $query->where('status', '1')
                     ->with('belongsToCreater')
                     ->with('belongsToGoods')
                     ->with('belongsToServiceDetail')
                     ->orderBy('id', 'DESC');

        if($queryList['withNoPage']){ //无分页,全部返还

            return $query->get();
        }else{

            return $query->paginate(10);
        }

        
    }

    // 获得所有库存明细
    public function getInventoryDetails()
    {   
        return InventoryDetail::select($this->select_columns)
                       ->with('belongsToCreater')
                       ->with('belongsToGoods')
                       ->with('belongsToServiceDetail')
                       // ->with('belongsToServiecDetail')
                       ->where('status', '1')
                       ->get();
    }

    // 创建库存明细
    public function create($requestData)
    {   
        // dd($requestData->all());
        $package_obj = (object) '';
        DB::transaction(function() use ($requestData, $package_obj){

            $requestData['creater_id']    = Auth::id();
            $requestData['status']        = '1';
            //$requestData['name']          = $requestData['package_name'];

            // dd($requestData->all());
            
            $package = new InventoryDetail();
            $input =  array_replace($requestData->all());
            $package->fill($input);

            $package = $package->create($input);
            // dd($package);
            // dd($requestData->month_price);
           
            foreach ($requestData->return_moon_price_list as $key => $price) {

                $package_info = new InventoryDetailInfo(); //库存明细信息对象

                $package_info->pid           = $package->id;
                $package_info->nums          = $package->month_nums;
                $package_info->creater_id    = Auth::id();
                $package_info->return_month  = $price['key'];
                $package_info->return_price  = $price['price'];
                $package_info->save();

                // dd($package_info);
            }
            $package_obj->scalar = $package;         
        });
        return $package_obj;
    }

    // 修改库存明细
    public function update($requestData, $id)
    {   
        $package_obj = (object) '';
        DB::transaction(function() use ($requestData,$id,$package_obj){


            // dd($requestData->all());
            $package  = InventoryDetail::findorFail($id);
            $input    =  array_replace($requestData->all());

            // dd($package);
            // dd($package->hasManyInventoryDetailInfo);
            $package->fill($input)->save();
            // dd($package->hasManyInventoryDetailInfo);
            foreach ($package->hasManyInventoryDetailInfo as $key => $value) {
                //删除原有库存明细月返还信息
                $value->status = '0';
                $value->save();
                // dd(lastSql());
            }
            
            foreach ($requestData->return_moon_price_list as $key => $price) {
                //新建库存明细月返还信息
                $package_info = new InventoryDetailInfo(); //库存明细信息对象

                $package_info->pid          = $package->id;
                $package_info->nums         = $package->month_nums;
                $package_info->creater_id   = Auth::id();
                $package_info->return_month = $price['key'];
                $package_info->return_price = $price['price'];
                $package_info->save();
            }
            $package_obj->scalar = $package;
        });
        return $package_obj;
    }

    // 删除库存明细
    public function destroy($id)
    {
        try {
            $package = InventoryDetail::findorFail($id);
            $package->status = '0';
            $package->save();

            return $package;
           
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }      
    }

    //判断库存明细是否重复
    public function isRepeat($requestData){

        $package = InventoryDetail::select('id', 'name')
                        ->where('name', $requestData->name)
                        ->where('package_price', $requestData->package_price)
                        ->where('month_nums', $requestData->month_nums)
                        ->where('status', '1')
                        ->first();
        // dd(isset($cate));
        return $package;
    }
}
