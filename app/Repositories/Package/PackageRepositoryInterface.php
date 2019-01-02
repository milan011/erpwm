<?php
namespace App\Repositories\Package;
 
interface PackageRepositoryInterface
{
    
    public function find($id);
    
    public function getAllPackage();

    public function create($requestData);

    public function update($id, $requestData);

    public function destroy($id);

    // public function getChildCategoryByBrandId($brand_id);
}
