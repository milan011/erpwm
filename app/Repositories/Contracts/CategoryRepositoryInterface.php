<?php

namespace App\Repositories\Contracts;

use App\Repositories\BaseInterface\RepositoryInterface;


/**
 * Created by PhpStorm.
 * User: zxwwjs@hotmail.com
 * @see \App\Repositories\CategoryRepository
 */
interface CategoryRepositoryInterface extends RepositoryInterface
{


    public function save($data, $id);
}
