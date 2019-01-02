<?php
namespace App\Repositories\Goods;
 
interface GoodsRepositoryInterface
{
    
    public function find($id);
    
    public function getAllGoods();

    public function create($requestData);

    public function update($id, $requestData);

    public function destroy($id);

    // public function getChildCategoryByBrandId($brand_id);
}
