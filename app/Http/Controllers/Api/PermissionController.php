<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\Permission\PermissionResource;

class PermissionController extends Controller
{
    public function __construct(){
    
    }

    /**
     * 所有权限列表(分页)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $permissions = Permission::where('status', '1')
                                 ->orderBy('created_at', 'DESC')
                                 ->paginate(10);

        // return new UserResource($users);

        return $permissions;
    }

    /**
     * 所有权限列表(无分页)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function permissionAll(Request $request)
    {
        $permissions = Permission::select('id', 'name', 'description', 'group')
                                 ->where('status', '1')
                                 ->get();
        $permissions = $permissions->groupBy('group');

        return new PermissionResource($permissions);
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

        return Permission::create($data);
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
        // dd(Permission::findOrFail($id));
        $data = $request->except(['token']);

        $permission = Permission::findOrFail($id)->update($data);

        return response([
                'status' => 'success'
            ]);

        // return $permission;
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
        $permission = Permission::findOrFail($id);
        $permission->status = '0';
        $permission->save();

        return response([
            'status' => 'success'
        ]);        
    }
}
