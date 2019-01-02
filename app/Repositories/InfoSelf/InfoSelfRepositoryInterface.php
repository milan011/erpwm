<?php
namespace App\Repositories\InfoSelf;
 
interface InfoSelfRepositoryInterface
{
    
    public function find($id);
    
    public function getAllInfos($requestData);

    public function create($requestData);

    public function update($id, $requestData);

    public function destroy($id);

    public function isRepeat($requestData);
}
