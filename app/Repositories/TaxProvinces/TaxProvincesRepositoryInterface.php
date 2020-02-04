<?php
namespace App\Repositories\TaxProvinces;

interface TaxProvincesRepositoryInterface
{

    public function find($id);

    public function getList($queryList);

    public function create($requestData);

    public function update($id, $requestData);

    public function destroy($id);
}
