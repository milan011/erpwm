<?php

namespace App\Repositories\Contracts;

use App\Repositories\BaseInterface\RepositoryInterface;


/**
 * Created by PhpStorm.
 * User: zxwwjs@hotmail.com
 */
interface RoleRepositoryInterface extends RepositoryInterface
{




    public function save($data);

    public function get_role_and_permissions($id);

    public function save_permission($roleId, $permissions);
}
