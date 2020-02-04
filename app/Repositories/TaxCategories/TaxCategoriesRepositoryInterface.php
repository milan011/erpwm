<?php
namespace App\Repositories\TaxCategories;

interface TaxCategoriesRepositoryInterface
{

    public function find($id);

    public function getList($requestData);

    public function create($requestData);

    public function update($id, $requestData);

    public function destroy($id);

    public function isRepeat($taxcatname);
}
