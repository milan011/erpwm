<?php
namespace App\Repositories\PackageInfo;
 
interface PackageInfoRepositoryInterface
{
    
    public function find($id);
    
    public function getAllPackageInfo();

    public function create($requestData);

    public function update($id, $requestData);

    public function destroy($id);

    // public function getChildCategoryByBrandId($brand_id);
}
