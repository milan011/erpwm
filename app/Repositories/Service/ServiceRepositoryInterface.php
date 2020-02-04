<?php
namespace App\Repositories\Service;
 
interface ServiceRepositoryInterface
{
    
    public function find($id);
    
    public function getAllService();

    public function create($requestData);

    public function update($id, $requestData);

    public function destroy($id);

    // public function getChildCategoryByBrandId($brand_id);
}
