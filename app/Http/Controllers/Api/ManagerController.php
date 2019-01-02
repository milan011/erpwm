<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Auth;
use Gate;
use DB;
use Carbon;
use App\Manager;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\Manager\ManagerRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Http\Resources\Manager\ManagerResource;
use App\Http\Resources\Manager\ManagerResourceCollection;
/*use App\Repositories\Car\CarRepositoryContract;
use App\Repositories\Shop\ShopRepositoryContract;
use App\Http\Requests\Cars\UpdateCarsRequest;
use App\Http\Requests\Cars\StoreCarsRequest;*/

class ManagerController extends Controller
{   
    protected $manager;
    protected $user;


    public function __construct(

        ManagerRepositoryInterface $manager,
        UserRepositoryInterface $user
    ) {
    
        $this->manager = $manager;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     * 所有车源列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {     
        
        $all_manager = $this->manager->getAllManagers($request);
        // dd($all_manager->links());
        // 
        
        // dd(new ManagerResource($all_manager));
        
        return new ManagerResource($all_manager);
    }

    /**
     * 所有客户经理列表(无分页)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function managerAll(Request $request)
    {
        $managers = Manager::select('id', 'name', 'telephone', 'first_letter')
                                 ->where('status', '1')
                                 ->orderBy('first_letter', 'ASC')
                                 ->get();
        // $managers = $managers->groupBy('first_letter');

        return new ManagerResource($managers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $repeat_user = $this->isRepeat($request->name, $request->telephone);
        // p(lastSql());
        // dd($repeat_user);

        if($repeat_user){
            return $this->baseFailed($message = '客户经理存在');
        }

        $new_manager = $this->manager->create($request);
        
        // dd($new_manager);
        if($new_manager){ //添加成功
            return $this->baseSucceed($respond_data = $new_manager, $message = '添加成功');
        }else{  //添加失败
            return $this->baseFailed($message = '内部错误');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $manager = $this->manager->find($id);

        // dd($cars->hasManyImages()->get());
        return view('admin.manager.show', compact('manager'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $manager = $this->manager->find($id);
        // dd($manager);
        return view('admin.manager.edit', compact(
            'manager'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $managerRequest, $id)
    {
        // dd($managerRequest->all());
        $manager = Manager::select('id', 'name', 'telephone', 'created_at')
                          ->find($id);
        $change_manager = $this->isRepeat($managerRequest->name, $managerRequest->telephone);

        if($change_manager && ($change_manager->id != $id)){
            return $this->baseFailed($message = '您修改后的客户经理信息与现有客户经理冲突');
        }

        $manager = $this->manager->update($managerRequest, $id);

        return $this->baseSucceed($respond_data = $manager, $message = '修改成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->manager->destroy($id); 

        return $this->baseSucceed($message = '修改成功');
    }

    //判断客户经理是否重复
    protected function isRepeat($name, $telephone){

        $manager = Manager::select('id', 'name', 'telephone', 'created_at')
                          ->where('name', $name)
                          ->where('telephone', $telephone)
                          ->where('status', '1')
                          ->first();
        // dd($manager);
        return $manager;
    }
}
