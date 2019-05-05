<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
// use App\ShopPrice;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Shop\ShopRepositoryInterface;
use App\Http\Resources\Shop\ShopResource;
use App\Http\Resources\Shop\ShopResourceCollection;
//use App\Http\Requests\Shop\UpdateShopRequest;
//use App\Http\Requests\Package\StorePackageRequest;

class ShopController extends Controller
{
    protected $shop;

    public function __construct(

        ShopRepositoryInterface $shop
    ) {
    
        $this->shop = $shop;
        // $this->middleware('brand.create', ['only' => ['create']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());

        $shops = $this->shop->getAllShop();

        // dd($shops);
        return new ShopResource($shops);
    }

    /**
     * 所有门店列表(无分页)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function shopAll(Request $request)
    {  
        $shops = $this->shop->getShops();

        return new ShopResource($shops);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.shop.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $shopRequest)
    {
        dd($shopRequest->all());
        if($this->shop->isRepeat($shopRequest)){
            return $this->baseFailed($message = '该门店已存在');
        }

        $new_shop = $this->shop->create($shopRequest);
        $new_shop->belongsToCreater;

        if($new_shop){ //添加成功
            return $this->baseSucceed($respond_data = $new_shop, $message = '添加成功');
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
    public function getShop($id)
    {
        $shop = $this->shop->find($id);
        $shop->hasManyShopInfo;

        return $this->baseSucceed($respond_data = $shop, $message = '');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shop      = $this->shop->find($id); //门店详情
        $shop_info = $shop->hasManyShopInfo->toJson(); //门店返还详情

        // dd($shop);
        // dd($shop_info);
        return view('admin.shop.edit', compact('shop', 'shop_info'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $shopRequest, $id)
    {
        // dd($shopRequest->all());
        $update_shop = $this->shop->isRepeat($shopRequest);
        if($update_shop && ($update_shop->id != $id)){
            return $this->baseFailed($message = '您修改后的门店信息与现有门店冲突');
        }
        $shop = $this->shop->update($shopRequest, $id);
        $shop->belongsToCity;
        // dd(redirect()->route('shop.index'));
        return $this->baseSucceed($respond_data = $shop, $message = '修改成功');
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
        $this->shop->destroy($id);        
        return $this->baseSucceed($message = '修改成功');
    }

}
