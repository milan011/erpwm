<?php
namespace App\Repositories\MaintenanceTask;

interface MaintenanceTaskRepositoryInterface
{

    public function find($id);

    public function getList($queryList);

    public function create($requestData);

    public function update($id, $requestData);

    public function destroy($id);

    public function isRepeat($taxcatname);
}
