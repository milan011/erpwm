<?php

namespace App\Repositories\User;

// use App\Models\Role;
use App\Repositories\BaseInterface\Repository;
// use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\User;
use Spatie\Permission\Models\Role;
use DB;
use Auth;

// class UserRepository extends Repository implements UserRepositoryInterface
class UserRepository implements UserRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id', 'name', 'nick_name', 'telephone', 'creater_id', 'status', 'created_at', 'remark'];

    public function model()
    {
        return "App\User";
    }

    // 根据ID获取用户信息
    public function find($id)
    {
        return User::select($this->select_columns)
                       ->findOrFail($id);
    }

    // 获得所有用户
    public function getUsers()
    {   
        return User::select($this->select_columns)
                   ->where('status', '1')
                   ->get();
    }

    /*public function get_user_and_role($userId)
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
    }*/

    public function create($requestData) {

        DB::beginTransaction();
        try {

            $password = bcrypt($requestData->password);
            $input = array_replace($requestData->all(), ['password' => "$password", 'creater_id' => Auth::id()]);
            $user = User::create($input);

            $newRoles = Role::where('name', 'manager')->get();
            $user->syncRoles($newRoles);
            
            /*if (count($requestData->roles) > 0) {
                $newRoles = Role::whereIn('id', $attributes['roles'])->get();
                $user->syncRoles($newRoles);
            }*/
            DB::commit();
            return $user;

        } catch (\Exception $e) {
            throw $e;
            DB::rollBack();
            return false;
        }
        //$password = bcrypt($requestData->password);
        /*$role_id = $requestData->role_id;

        $role_info = Role::findOrFail($role_id);*/

        /*p($requestData->agents_total);
        p($requestData->agents_frist);
        dd($requestData->agents_secend);*/
        //设置用户pid
        /*if(!empty($requestData->agents_secend)){
            // 有二级代理
            $pid = $requestData->agents_secend;
        }else if(!empty($requestData->agents_frist)){
            // 有一级代理
            $pid = $requestData->agents_frist;
        }else{
            // 总代理
            $pid = $requestData->agents_total;
        }*/

        // 添加用户到用户表
        //$input = array_replace($requestData->all(), ['password' => "$password", 'creater_id' => Auth::id()]);

        // dd($input);

        //$user = User::create($input);

        // 关联用户表与用户-角色表
        /*$userRole = new RoleUser;
        $userRole->role_id = '3';
        $userRole->user_id = $user->id;
        $userRole->save();

        Session::flash('sucess', '添加用户成功');
        return $user;*/
    }

    public function update($id, $requestData) {

        DB::beginTransaction();
        try {

            $user = User::findorFail($id);

            $user->nick_name = $requestData->nick_name;
            $user->telephone = $requestData->telephone;
            $user->remark    = $requestData->remark;

            // 更新用户
            $user->save();
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            throw  $e;
            DB::rollBack();
            return false;
        }

        // dd($requestData->all());
        //$user = User::with(tableUnionDesign('hasManyRoles', ['roles.id', 'name', 'slug']))
            //->findorFail($id);

        /*p($requestData->role_id);
        dd($user->hasManyRoles[0]->id);*/

        // $user->name = $requestData->name;
        //$user->nick_name = $requestData->nick_name;
        //$user->telephone = $requestData->telephone;
        //$user->wx_number = $requestData->wx_number;
        //$user->email     = $requestData->email;
        //$user->remark    = $requestData->remark;

        // 更新用户
        //$user->save();

        //如果角色有变化，更新UserRole表
        /*if ($requestData->role_id != $user->hasManyRoles[0]->id) {

            $user_id = $id; //当前用户ID
            $role_id = $user->hasManyRoles[0]->id; //角色ID
            // 获得需要更新的对象
            $user_role = RoleUser::where('user_id', $user_id)
                ->where('role_id', $role_id)
                ->first();
            // dd($requestData->role_id);
            $user_role->role_id = $requestData->role_id;

            $user_role->save();
        }*/

        //Session()->flash('sucess', '更新用户成功');

        //return $user;
    }
}
