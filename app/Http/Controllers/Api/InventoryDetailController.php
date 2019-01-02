<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
// use App\InventoryDetailPrice;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\InventoryDetail\InventoryDetailRepositoryInterface;
use App\Http\Resources\InventoryDetail\InventoryDetailResource;
use App\Http\Resources\InventoryDetail\InventoryDetailResourceCollection;
//use App\Http\Requests\InventoryDetail\UpdateInventoryDetailRequest;
//use App\Http\Requests\Package\StorePackageRequest;

class InventoryDetailController extends Controller
{
    protected $inventory;

    public function __construct(

        InventoryDetailRepositoryInterface $inventory
    ) {
    
        $this->inventory = $inventory;
        // $this->middleware('brand.create', ['only' => ['create']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query_list = jsonToArray($request->input('query')); //获取搜索信息

        // dd($query_list);

        $inventorys = $this->inventory->getAllInventoryDetail($query_list);
        // dd($inventorys[1]->belongsToServiceDetail->hasManyServiceDetailGoods);       

        foreach ($inventorys as $key => $value) {
            $value->belongsToServiceDetail->hasManyServiceDetailGoods;
        }

        
       // dd($inventorys);
        /*$inventorys = $inventorys->filter(function ($value, $key) {
            if(!empty($value->belongsToGoods)){
                return $value;
            }
        });

        dd($inventorys);*/
        return new InventoryDetailResource($inventorys);
    }

    /**
     * 所有套餐列表(无分页)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function inventoryAll(Request $request)
    {
        $inventorys = $this->inventory->getInventoryDetails();

        return new InventoryDetailResource($inventorys);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.inventory.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $inventoryRequest)
    {
        // dd($inventoryRequest->all());
        $new_inventory = $this->inventory->create($inventoryRequest);
        // $new_inventory->belongsToCreater;
        // dd($new_inventory);
        if($new_inventory){ //添加成功
            return $this->baseSucceed($respond_data = $new_inventory, $message = '添加成功');
        }else{  //添加失败
            return $this->baseFailed($message = '内部错误');
        }   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getInventoryDetail($id)
    {
        $inventory = $this->inventory->find($id);
        $inventory->hasManyInventoryDetailInfo;

        return $this->baseSucceed($respond_data = $inventory, $message = '');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inventory      = $this->inventory->find($id); //套餐详情
        $inventory_info = $inventory->hasManyInventoryDetailInfo->toJson(); //套餐返还详情

        // dd($inventory);
        // dd($inventory_info);
        return view('admin.inventory.edit', compact('inventory', 'inventory_info'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $inventoryRequest, $id)
    {
        // dd($inventoryRequest->all());
        $update_inventory = $this->inventory->isRepeat($inventoryRequest);
        if($update_inventory && ($update_inventory->id != $id)){
            return $this->baseFailed($message = '您修改后的套餐信息与现有套餐冲突');
        }
        $inventory = $this->inventory->update($inventoryRequest, $id);
        // dd(redirect()->route('inventory.index'));
        return $this->baseSucceed($respond_data = $inventory, $message = '修改成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        // dd('删了');
        $this->inventory->destroy($id);        
        return $this->baseSucceed($message = '修改成功');
    }

    //ajax判断车型是否重复
    public function checkRepeat(Request $request){

        // dd($request->all());
        if($this->category->isRepeat($request)){
            //车型重复
            return response()->json(array(
                'status' => 1,
                // 'data'   => $category,
                'message'   => '系列名称重复'
            ));
        }else{
            //车型不重复
            return response()->json(array(
                'status' => 0,
                'message'   => '系列名称不重复'
            ));
        }
    }
}
