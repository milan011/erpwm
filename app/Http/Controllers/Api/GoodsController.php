<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
// use App\GoodsPrice;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Goods\GoodsRepositoryInterface;
use App\Http\Resources\Goods\GoodsResource;
use App\Http\Resources\Goods\GoodsResourceCollection;
//use App\Http\Requests\Goods\UpdateGoodsRequest;
//use App\Http\Requests\Package\StorePackageRequest;

class GoodsController extends Controller
{
    protected $goods;

    public function __construct(

        GoodsRepositoryInterface $goods
    ) {
    
        $this->goods = $goods;
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

        $goodss = $this->goods->getAllGoods();

        // dd($goodss);
        return new GoodsResource($goodss);
    }

    /**
     * 所有商品列表(无分页)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function goodsAll(Request $request)
    {  
        $goodss = $this->goods->getGoodss();

        return new GoodsResource($goodss);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.goods.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $goodsRequest)
    {
        // dd($goodsRequest->all());
        if($this->goods->isRepeat($goodsRequest)){
            return $this->baseFailed($message = '该商品已存在');
        }

        $new_goods = $this->goods->create($goodsRequest);
        $new_goods->belongsToCreater;

        if($new_goods){ //添加成功
            return $this->baseSucceed($respond_data = $new_goods, $message = '添加成功');
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
    public function getGoods($id)
    {
        $goods = $this->goods->find($id);
        $goods->hasManyGoodsInfo;

        return $this->baseSucceed($respond_data = $goods, $message = '');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $goods      = $this->goods->find($id); //商品详情
        $goods_info = $goods->hasManyGoodsInfo->toJson(); //商品返还详情

        // dd($goods);
        // dd($goods_info);
        return view('admin.goods.edit', compact('goods', 'goods_info'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $goodsRequest, $id)
    {
        // dd($goodsRequest->all());
        $update_goods = $this->goods->isRepeat($goodsRequest);
        if($update_goods && ($update_goods->id != $id)){
            return $this->baseFailed($message = '您修改后的商品信息与现有商品冲突');
        }
        $goods = $this->goods->update($goodsRequest, $id);
        // dd(redirect()->route('goods.index'));
        return $this->baseSucceed($respond_data = $goods, $message = '修改成功');
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
        $this->goods->destroy($id);        
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
