<?php
namespace App\Repositories\PcAssignCashToTab;

interface PcAssignCashToTabRepositoryInterface
{

    public function find($id);

    public function getList($queryList);

    public function create($requestData);

    public function update($id, $requestData);

    public function destroy($id);

    public function isRepeat($taxcatname);
}
