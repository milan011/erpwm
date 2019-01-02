<?php
namespace App\Repositories\Inventory;

use App\Inventory;
use App\InventoryDetail;
use App\Goods;
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
use App\Repositories\Inventory\InventoryRepositoryInterface;
use App\Repositories\InventoryDetail\InventoryDetailRepositoryInterface;

class InventoryRepository implements InventoryRepositoryInterface
{

    //默认查询数据
    protected $select_columns = ['id','goods_id', 'inventory_now',  'created_at', 'updated_at'];
    protected $inventoryDetail;

    public function __construct(

        InventoryDetailRepositoryInterface $inventoryDetail
    ) {
    
        $this->inventoryDetail = $inventoryDetail;
        // $this->middleware('brand.create', ['only' => ['create']]);
    }

    // 根据ID获得库存信息
    public function find($id)
    {
        return Inventory::select($this->select_columns)
                       ->findOrFail($id);
    }

    // 获得库存列表
    public function getAllInventory($query_list)
    {   
        $goodsNowList = [];
        $goodsNow = Goods::where('status', '1')->select('id')->get(); //当前有效商品
        

        foreach ($goodsNow as $key => $value) {
            $goodsNowList[] = $value->id;
        }

        // dd($goodsNowList);
        $query = new Inventory();       // 返回的是一个Plan实例,两种方法均可
        // dd($request->all());
        $query = $query->addCondition($query_list); //根据条件组合语句

        return $query->where('status', '1')
                     /*->with(['belongsToGoods'=>function($query){
                         $query->where('status','1');
                     }])*/
                     ->whereIn('id', $goodsNowList)
                     ->with('belongsToGoods') 
                     ->orderBy('created_at', 'DESC')
                     ->paginate(10);
    }

    // 获得历史有库存
    public function getHistoryInventory($query_list)
    {   
        //首选查询当前库存,再根据需查询的历史时间点,查询历史时间点到现在所有库存明细,计算出历史库存
        // p($query_list);
        $date_now = Carbon::tomorrow()->toDateTimeString();

        // dd($date_now);
        //当前库存状态
        $inventory_now = $this->getAllInventory($query_list);

        // dd($this->inventoryDetail);
        foreach ($inventory_now as $key => $value) {
            // dd($value);
            $query['goods_id']   = $value['goods_id'];
            $query['withNoPage'] = true;
            $query['selectDate'] = array(
                $query_list['inventoryDate'],
                $date_now
            );
            // dd($query);
            $inventory_details = $this->inventoryDetail->getAllInventoryDetail($query);
            // dd(lastSql());
            foreach ($inventory_details as $k => $v) {
                # code...
                // dd($v);
                if($v->inventory_type == '1'){
                    //入库
                    /*p('入库');
                    dd($v->goods_nums);*/
                    $value->inventory_now += $v->goods_nums;
                }else{
                    //出库
                    /*p('出库');
                    dd($v->goods_nums);*/
                    $value->inventory_now -= $v->goods_nums;
                }
            }
            // dd($inventory_details);
        }

        return $inventory_now;
    }

    // 创建库存
    public function create($requestData)
    {   
        // dd($requestData->all());
        DB::beginTransaction();
        try {
            //库存添加

            $inventory = Inventory::findorFail($requestData->id);
            
            $inventory->goods_id      = $requestData->goods_id;
            $inventory->inventory_now = $requestData->inventory_now + $requestData->goods_num;

            $inventory->save();
            /*p('22');
            dd($newInventory);*/

            $newInventoryDetail = new InventoryDetail();

            //入库单号
            $date = (string) (time());
            $date = substr($date, 1);
            if($requestData->inventory_type == '1'){
                $code = 'I-'.$date;
            }else{
                $code = 'O-'.$date;
            }

            // dd($requestData->inventory_type);
            // dd($requestData->$inventory_type);
            
            $newInventoryDetail->goods_id        = $requestData->goods_id;
            $newInventoryDetail->inventory_code  = $code;
            $newInventoryDetail->inventory_type  = $requestData->inventory_type;
            // $newInventoryDetail->inventory_price = $requestData->goods_in_price;
            $newInventoryDetail->goods_nums      = $requestData->goods_num;
            $newInventoryDetail->creater_id      = Auth::id();

            $detail = $newInventoryDetail->save();


            //出入库明细添加

            DB::commit();
            return $inventory;
        } catch (\Exception $e) {
            throw $e;
            DB::rollBack();
            return false;
        }
        // dd($requestData->all());
        /*$package_obj = (object) '';
        DB::transaction(function() use ($requestData, $package_obj){

            $requestData['creater_id']    = Auth::id();
            $requestData['status']        = '1';
            //$requestData['name']          = $requestData['package_name'];

            // dd($requestData->all());
            
            $package = new Inventory();
            $input =  array_replace($requestData->all());
            $package->fill($input);

            $package = $package->create($input);
            // dd($package);
            // dd($requestData->month_price);
           
            foreach ($requestData->return_moon_price_list as $key => $price) {

                $package_info = new InventoryInfo(); //库存信息对象

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
        return $package_obj;*/
    }

    // 修改库存
    public function update($requestData, $id)
    {   
        $package_obj = (object) '';
        DB::transaction(function() use ($requestData,$id,$package_obj){


            // dd($requestData->all());
            $package  = Inventory::findorFail($id);
            $input    =  array_replace($requestData->all());

            // dd($package);
            // dd($package->hasManyInventoryInfo);
            $package->fill($input)->save();
            // dd($package->hasManyInventoryInfo);
            foreach ($package->hasManyInventoryInfo as $key => $value) {
                //删除原有库存月返还信息
                $value->status = '0';
                $value->save();
                // dd(lastSql());
            }
            
            foreach ($requestData->return_moon_price_list as $key => $price) {
                //新建库存月返还信息
                $package_info = new InventoryInfo(); //库存信息对象

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

    // 删除库存
    public function destroy($id)
    {
        try {
            $package = Inventory::findorFail($id);
            $package->status = '0';
            $package->save();

            return $package;
           
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }      
    }

    public function isEnoughInventory($goodsList){

        // dd($goodsList);

        foreach ($goodsList as $key => $value) {
            if(!empty($value['goodsId'])){
                $inventoryInfo = Inventory::findorFail($value['goodsId']);
                // dd($inventoryInfo);
                if($value['num'] > $inventoryInfo->inventory_now){
                    return false;
                }
            }
        }
        return true;
    }
}
