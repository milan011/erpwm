<?php

namespace App\Repositories;

use App\Models\Role;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends Repository implements UserRepositoryInterface
{

    public function model()
    {
        return "App\Models\User";
    }

    public function get_user_and_role($userId)
    {
        $user = $this->model->find($userId);
        if (!$user) {
            return null;
        }
        $roles = $user->roles()->pluck('id');
        $user['roles'] = $roles;
        return $user;
    }

    public function save_role($user_id, $roles)
    {
        $user = $this->model->find($user_id);
        if (!$user) {
            return false;
        }

        $qRoles = array_map(function ($v) {
            $v = intval($v) ? intval($v) : 0;
            $v = $v > 0 ? $v : null;
            return $v;
        }, $roles);

        $qRoles = array_filter($qRoles);
        $roleIds = [];

        if (count($qRoles) > 0) {
            $roleIds = Role::find($roles)->pluck('id');
        }
  
        $user->roles()->sync($roleIds);
        return true;
    }

    public function save($data, $id = null)
    {
        $id = intval($id) > 0 ? intval($id) : null;

        $result = $this->createOrUpdate($data, $id);
        if (!$result) {
            return false;
        }
        $result['roles'] = $result->roles()->pluck('id');
        return $result;
    }
}
