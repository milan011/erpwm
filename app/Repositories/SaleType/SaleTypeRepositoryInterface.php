<?php
namespace App\Repositories\SaleType;

interface SaleTypeRepositoryInterface
{

    public function find($id);

    public function getList($query_list);

    public function create($requestData);

    public function update($id, $requestData);

    public function destroy($id);

    // public function getChildCategoryByBrandId($brand_id);
}
