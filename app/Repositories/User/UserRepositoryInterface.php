<?php

// namespace App\Repositories\Contracts;
namespace App\Repositories\User;

use App\Repositories\BaseInterface\RepositoryInterface;


/**
 * Created by PhpStorm.
 * User: zxwwjs@hotmail.com
 */
interface UserRepositoryInterface
{


    /*public function get_user_and_role($userId);

    public function save_role($user_id, $roles);

    public function save($data,$id);*/

    public function create($requestData);

    public function update($id, $requestData);



}
