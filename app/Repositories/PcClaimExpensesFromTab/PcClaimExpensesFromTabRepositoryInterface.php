<?php
namespace App\Repositories\PcClaimExpensesFromTab;

interface PcClaimExpensesFromTabRepositoryInterface
{

    public function find($id);

    public function getList($queryList);

    public function create($requestData);

    public function update($id, $requestData);

    public function destroy($id);

    public function isRepeat($taxcatname);
}
