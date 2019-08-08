<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\InternalStockCategoriesByRole;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {

    }

    /**
     * 所有权限列表
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // p($request->input('query'));
        $role = Role::where('status', '1')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        // return new UserResource($users);

        return $role;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $data = $request->except(['token']);

        return Role::create($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // dd(Role::findOrFail($id));
        $data = $request->except(['token']);

        $role = Role::findOrFail($id)->update($data);

        return response([
            'status' => 'success',
        ]);

        // return $role;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {

        // throw new \App\ApiExceptions\ApiException('添加失败');
        $role         = Role::findOrFail($id);
        $role->status = '0';
        $role->save();

        return response([
            'status' => 'success',
        ]);
    }

    // 获取角色权限列表
    public function getRolePermissions($id)
    {

        $role        = Role::findOrFail($id);
        $permissions = $role->permissions()->get();

        $list = [];
        foreach ($permissions as $key => $value) {
            $list[] = $value->name;
        }
        // dd($list);

        return $list;
    }

    // 编辑角色权限列表
    public function giveRolePermissions($id, Request $request)
    {

        $role        = Role::findOrFail($id);
        $permissions = $request->post('permissions');
        /*p($role);
        dd($permissions);*/
        $role->syncPermissions($permissions);

        return response([
            'status' => 'success',
        ]);
    }

    //获取角色物料组
    public function getRoleStockCategory($id)
    {
        // dd($id);
        $roleStock = InternalStockCategoriesByRole::where('secroleid', $id)->get();
        // dd($roleStock);

        $stock_list = [];

        if (!empty($roleStock)) {
            foreach ($roleStock as $key => $value) {
                $stock_list[] = (int) $value->categoryid;
            }
        }

        // dd($stock_list);
        return response([
            'data' => $stock_list,
        ]);

    }

    //分配角色物料组
    public function giveRoleStockCategory($id, Request $request)
    {
        // p($id);
        // dd($request->all());
        if (!empty($request->list)) {
            //有物料组
            $data_list = [];
            foreach ($request->list as $key => $value) {
                $data_list[$key]['categoryid'] = (string) $value;
                $data_list[$key]['secroleid']  = $id;
            }
            DB::beginTransaction();
            try {

                //删除现在关联
                DB::table('internalstockcatrole')->where('secroleid', $id)->delete();

                $stock = new InternalStockCategoriesByRole();

                foreach ($data_list as $key => $value) {
                    $input = array_replace($value);
                    $stock->fill($input);
                    $stock = $stock->create($input);
                }

                DB::commit();
                return $stock;

            } catch (\Exception $e) {
                throw $e;
                DB::rollBack();
                return false;
            }
        } else {
            //没有选择物料组
            DB::table('internalstockcatrole')->where('secroleid', $id)->delete();
        }
    }
}
