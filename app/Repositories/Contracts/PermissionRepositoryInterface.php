<?php

namespace App\Repositories\Contracts;

use App\Repositories\BaseInterface\RepositoryInterface;


/**
 * Created by PhpStorm.
 * User: zxwwjs@hotmail.com
 */
interface PermissionRepositoryInterface extends RepositoryInterface
{



    public function save($data,$id = null);

    public function addRoute($uri, $method);
}
