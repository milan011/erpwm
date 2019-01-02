<?php
namespace App\Repositories\Goods;

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
use App\Repositories\Goods\GoodsRepositoryInterface;

class GoodsRepository implements GoodsRepositoryInterface
{

    //默认查询数据
    protected $select_columns = ['id','name', 'brand', 'goods_from', 'type', 'goods_spec', 'goods_unit', 'is_food', 'status', 'creater_id', 'remark', 'created_at', 'updated_at'];

    // 根据ID获得商品信息
    public function find($id)
    {
        return Goods::select($this->select_columns)
                       ->findOrFail($id);
    }

    // 获得商品列表
    public function getAllGoods()
    {   
        return Goods::where('status', '1')
                    ->with('belongsToCreater')
                    ->orderBy('created_at', 'DESC')
                    ->paginate(10);
    }

    // 获得所有商品
    public function getGoodss()
    {   
        return Goods::select($this->select_columns)
                    ->with('belongsToCreater')
                    ->where('status', '1')
                    ->get();
    }

    // 创建商品
    public function create($requestData)
    {   
        // dd($requestData->all());

        $requestData['creater_id']    = Auth::id();
        $requestData['status']        = '1';
        
        $goods = new Goods();
        $input =  array_replace($requestData->all());
        // $input =  array_filter($input);
        $goods->fill($input);
        // dd($goods);
        $newGoods = $goods->create($input);
        // dd($goods);
        // dd($requestData->month_price);
           
        return $newGoods;
    }

    // 修改商品
    public function update($requestData, $id)
    {   
        // dd($requestData->all());
        $goods  = Goods::findorFail($id);
        $input  = array_replace($requestData->all());
        // dd($goods);
        // dd($goods->hasManyGoodsInfo);
        $goods->fill($input)->save();

        return $goods;
    }

    // 删除商品
    public function destroy($id)
    {
        try {
            $goods = Goods::findorFail($id);
            $goods->status = '0';
            $goods->save();

            return $goods;
           
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }      
    }

    //判断商品是否重复
    public function isRepeat($requestData){

        $goods = Goods::select('id', 'name')
                        ->where('name', $requestData->name)
                        ->where('status', '1')
                        ->first();
        // dd(isset($cate));
        return $goods;
    }
}
