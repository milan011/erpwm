<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
// use App\ServicePrice;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Service\ServiceRepositoryInterface;
use App\Http\Resources\Service\ServiceResource;
use App\Http\Resources\Service\ServiceResourceCollection;
//use App\Http\Requests\Service\UpdateServiceRequest;
//use App\Http\Requests\Package\StorePackageRequest;

class ServiceController extends Controller
{
    protected $service;

    public function __construct(

        ServiceRepositoryInterface $service
    ) {
    
        $this->service = $service;
        // $this->middleware('brand.create', ['only' => ['create']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());
        
        $services = $this->service->getAllService();

        // dd($services);
        return new ServiceResource($services);
    }

    /**
     * 所有业务列表(无分页)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function serviceAll(Request $request)
    {
        $services = $this->service->getServices();

        return new ServiceResource($services);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.service.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $serviceRequest)
    {
        // dd($serviceRequest->all());
        if($this->service->isRepeat($serviceRequest)){
            return $this->baseFailed($message = '该业务已存在');
        }

        $new_service = $this->service->create($serviceRequest);
        $new_service->belongsToCreater;

        if($new_service){ //添加成功
            return $this->baseSucceed($respond_data = $new_service, $message = '添加成功');
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
    public function getService($id)
    {
        $service = $this->service->find($id);
        $service->hasManyServiceInfo;

        return $this->baseSucceed($respond_data = $service, $message = '');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service      = $this->service->find($id); //业务详情
        $service_info = $service->hasManyServiceInfo->toJson(); //业务返还详情

        // dd($service);
        // dd($service_info);
        return view('admin.service.edit', compact('service', 'service_info'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $serviceRequest, $id)
    {
        // dd($serviceRequest->all());
        $update_service = $this->service->isRepeat($serviceRequest);
        if($update_service && ($update_service->id != $id)){
            return $this->baseFailed($message = '您修改后的业务信息与现有业务冲突');
        }
        $service = $this->service->update($serviceRequest, $id);
        // dd(redirect()->route('service.index'));
        return $this->baseSucceed($respond_data = $service, $message = '修改成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        // dd('删了');
        $this->service->destroy($id);        
        return $this->baseSucceed($message = '修改成功');
    }
}
