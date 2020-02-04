<?php

namespace App\Repositories\Contracts;

use App\Repositories\BaseInterface\RepositoryInterface;


/**
 * Created by PhpStorm.
 * User: zxwwjs@hotmail.com
 */
interface UserRepositoryInterface extends RepositoryInterface
{


    public function get_user_and_role($userId);

    public function save_role($user_id, $roles);

    public function save($data,$id);


}
