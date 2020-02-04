<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\Contracts\RoleRepositoryInterface;

class RoleRepository extends Repository implements RoleRepositoryInterface
{

    /**
     * @return \App\Models\Role
     */
    public function model()
    {
        return "App\Models\Role";
    }


    public function save($data, $id = 0)
    {
        $id = intval($id);

        if ($id > 0) {
            $data = $this->updateRich($data, $id);
            $data['permissions'] = $data->permissions()->pluck('id');
        } else {
            $data = $this->create($data);
            $data['permissions'] = [];
        }
        return $data;
    }

    public function get_role_and_permissions($id)
    {
        $role = $this->model->find($id);
        if (!$role) {
            return null;
        }
        $permissions = $role->permissions()->pluck('id');

        $role['permissions'] = $permissions;
        return $role;
    }

    public function save_permission($roleId, $permissions)
    {
        $role = $this->model->find($roleId);
        if (!$role) {
            return false;
        }
        $qPermissions = array_map(function ($v) {
            $v = intval($v) ? intval($v) : 0;
            $v = $v > 0 ? $v : null;
            return $v;
        }, $permissions);

        $qPermissions = array_filter($qPermissions);
        $permissionsIds = [];

        if (count($qPermissions) > 0) {
            $permissionsIds = Permission::find($qPermissions)->pluck('id');
        }
        $role->permissions()->sync($permissionsIds);
        return true;
    }
}
