<?php

namespace App\Repositories;

use App\Repositories\BaseInterface\Repository;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use App\Models\Permission;
use bar\baz\source_with_namespace;

class PermissionRepository extends Repository implements PermissionRepositoryInterface
{

    public function model()
    {
        return "App\Models\Permission";
    }


    public function save($data, $id = null)
    {
        $id = intval($id);

        if ($id > 0) {
            $data = $this->updateRich($data, $id);
            $data['roles'] = $data->permissions()->pluck('id');
        } else {
            $data = $this->create($data);
            $data['roles'] = [];
        }
        return $data;
    }

    public function addRoute($name, $method)
    {
        if (!$this->findBy('name', $name)) {
            $data = ['name'=>$name];
            $data['display_name'] = str_replace("_"," ",strtolower($name));
            $data['description'] = str_replace("_"," ",strtolower($name));
            return  $this->create($data);
        }
        return false;
    }
}
