<?php
namespace App\Repositories\PcAuthorizeExpense;

interface PcAuthorizeExpenseRepositoryInterface
{

    public function find($id);

    public function getList($queryList);

    public function create($requestData);

    public function update($id, $requestData);

    public function approval($id);

    public function isRepeat($taxcatname);
}
