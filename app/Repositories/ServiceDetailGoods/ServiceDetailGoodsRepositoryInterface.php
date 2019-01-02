<?php
namespace App\Repositories\ServiceDetailGoods;
 
interface ServiceDetailGoodsRepositoryInterface
{
    
    public function find($id);
    
    public function getAllServiceDetailGoods();

    public function create($requestData);

    public function update($id, $requestData);

    public function destroy($id);

    // public function getChildCategoryByBrandId($brand_id);
}
