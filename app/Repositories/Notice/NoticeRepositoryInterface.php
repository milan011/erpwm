<?php
namespace App\Repositories\Notice;
 
interface NoticeRepositoryInterface
{
    
    public function find($id);
    
    public function allNotices();

    public function create($requestData);

    public function update($id, $requestData);

    public function destroy($id);

    // public function getChildCategoryByBrandId($brand_id);
}
