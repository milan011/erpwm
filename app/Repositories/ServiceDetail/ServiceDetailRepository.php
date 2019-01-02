<?php
namespace App\Repositories\ServiceDetail;

use App\ServiceDetail;
use App\ServiceDetailGoods;
use App\Service;
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
use App\Repositories\ServiceDetail\ServiceDetailRepositoryInterface;
use App\Http\Controllers\Api\Traits\BaseResponseTrait;

class ServiceDetailRepository implements ServiceDetailRepositoryInterface
{
    use BaseResponseTrait;
    //默认查询数据
    protected $select_columns = ['id', 'name', 'customer', 'service_id', 'charge_price', 'inventory_percentage','inventory_profit', 'goods_cost', 'inventer_ticheng', 'status', 'goods_num', 'customer_telephone', 'remark', 'creater_id', 'created_at', 'updated_at'];

    // 根据ID获得业务信息
    public function find($id)
    {
        return ServiceDetail::select($this->select_columns)
                            ->with('hasManyInventoryDetail')
                            ->with('hasManyServiceDetailGoods')
                            ->findOrFail($id);
    }

    // 获得业务列表
    public function getAllServiceDetail($query_list)
    {   
        $query = new ServiceDetail();       // 返回的是一个Plan实例,两种方法均可
        // dd($request->all());
        $query = $query->addCondition($query_list); //根据条件组合语句

        return $query->where('status', '1')
                     ->with('belongsToCreater')
                     ->with('hasManyServiceDetailGoods')
                     ->orderBy('created_at', 'DESC')
                     ->paginate(10);
    }

    // 获得所有业务
    public function getServiceDetails()
    {   
        return ServiceDetail::select($this->select_columns)
                       ->where('status', '1')
                       ->get();
    }

    // 创建业务
    public function create($requestData)
    {   
        DB::beginTransaction();
        try {
            /**
            *1.业务细节处理
            *2.业务礼品处理
            **/
            // dd($requestData->all());

            $serviceInfo = Service::findorFail($requestData->service_id);
            $g_list = $requestData->goodsIdList;
            // dd($g_list);

            // dd($serviceInfo);
            $inventory_percentage = 0; //业务佣金,按比例或按金额
            $goods_cost           = 0; //赠品金额
            $inventory_profit     = 0; //利润
            $goodsList            = []; //赠品列表
            $allGoodsNum          = 0; //赠品总数
            $inventer_ticheng     = 0; //提成

            foreach ( $g_list as $key => $value) {
                // 处理业务礼品/赠品,获取商品库存,判断是否有赠品及赠品数量是否足够
                // $goods_cost = 0;
                if(!empty($value['goodsId'])){
                    $goodsInfo = Goods::findorFail($value['goodsId']);
                    // dd($value);
                    $goods_p = ($goodsInfo->in_price) * ($value['num']); //当前赠品价格小计
                    $goods_cost += $goods_p;
                    $goodsList[$key]['goodsId'] = $value['goodsId'];
                    $allGoodsNum += $value['num'];
                    $goodsList[$key]['goods_price'] = $goods_p;
                    $goodsList[$key]['goods_num']   = $value['num'];
                    $goodsList[$key]['goods_name']  = $goodsInfo->name;
                    // dd($g_list);
                }
            }         

           /* p($inventory_profit);
            p($goods_cost);
            p($allGoodsNum);
            p($requestData->goodsIdList);
            dd($goodsList);*/    

            if($serviceInfo->type == '1'){
                //按比例
                $inventory_percentage = ($requestData->charge_price * $serviceInfo->return_ratio)/100;
            }else{
                //按金额
                $inventory_percentage = $serviceInfo->return_price;
            }

            $inventory_profit = $inventory_percentage - $goods_cost;
            $inventer_ticheng = $inventory_profit*25/100;

            // dd($serviceInfo);
            // dd($inventory_percentage);

            $requestData['creater_id']            = Auth::id();
            $requestData['status']                = '1';
            $requestData['name']                  = $serviceInfo->name;
            $requestData['goods_num']             = $allGoodsNum;
            $requestData['inventory_percentage']  = $inventory_percentage;
            $requestData['inventory_profit']      = $inventory_profit;
            $requestData['goods_cost']            = $goods_cost;
            $requestData['inventer_ticheng']      = $inventer_ticheng;
            
            $new_sevice = new ServiceDetail();
            $input =  array_replace($requestData->all());
            $new_sevice->fill($input);

            $new_sevice = $new_sevice->create($input);
            // dd($sevice_obj);
            // dd($requestData->month_price);
           
            foreach ($goodsList as $key => $g) {
                //添加业务赠品详情,对应赠品库存处理(减库存)
                $sevice_goods_info      = new ServiceDetailGoods(); //业务信息赠品对象
                $sevice_goods_inventory = Inventory::findorFail($g['goodsId']); //业务信息赠品库存对象
                $out_inventory          = new InventoryDetail(); //出库明细

                //业务赠品处理
                $sevice_goods_info->goods_id           = $g['goodsId'];
                $sevice_goods_info->service_detail_id  = $new_sevice->id;
                $sevice_goods_info->creater_id         = Auth::id();
                $sevice_goods_info->goods_num          = $g['goods_num'];
                $sevice_goods_info->goods_price        = $g['goods_price'];
                $sevice_goods_info->goods_name         = $g['goods_name'];
                $sevice_goods_info->save();

                //业务赠品库存处理
                $sevice_goods_inventory->inventory_now -= $g['goods_num'];
                $sevice_goods_inventory->save();

                //出库单号
                $date = (string) (time());
                $date = substr($date, 1);
                $code = 'O-'.$date;

                $out_inventory->inventory_type    = '2';
                $out_inventory->service_detail_id = $new_sevice->id;
                $out_inventory->inventory_code    = $code;
                $out_inventory->creater_id        = Auth::id();
                $out_inventory->goods_id          = $g['goodsId'];
                $out_inventory->goods_nums        = $g['goods_num'];
                $out_inventory->save();


                // dd($sevice_goods_info);
                // dd($sevice_goods_inventory);
            }
            //出入库明细添加

            DB::commit();
            return $new_sevice;
        } catch (\Exception $e) {
            throw $e;
            DB::rollBack();
            return false;
        }
    }

    // 删除业务
    public function destroy($id)
    {
        // dd($id);
        // 业务删除,出库明细废弃,赠品回库
        DB::beginTransaction();
        try {
            // $sevice_obj = ServiceDetail::findorFail($id);
            $sevice_obj = $this->find($id);
            $sevice_obj->status = '0';
            $sevice_obj->save();
            // dd($sevice_obj->hasManyInventoryDetail->count());
            if($sevice_obj->hasManyInventoryDetail->count() > 0){
                foreach ($sevice_obj->hasManyInventoryDetail as $key => $value) {
                    //明细废弃
                    /*dd($value);
                    dd($value->belongsToInventory);*/
                    $value->status = '0';

                    $value->save();

                    //库存添加
                    $inentoryGoods = $value->belongsToInventory; //对应库存商品信息
                    $inentoryGoods->inventory_now = $inentoryGoods->inventory_now + $value->goods_nums;
                    // $inentoryGoods->inventory_now = 22;
                    // dd($value->goods_nums);
                    // dd($inentoryGoods);
                    $inentoryGoods->save();
                }
            }
            DB::commit();
            return $sevice_obj;
           
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }      
    }
}
