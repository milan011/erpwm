<?php
namespace App\Repositories\Shop;
 
interface ShopRepositoryInterface
{
    
    public function find($id);
    
    public function getAllShop();

    public function create($requestData);

    public function update($id, $requestData);

    public function destroy($id);

    // public function getChildCategoryByBrandId($brand_id);
}
