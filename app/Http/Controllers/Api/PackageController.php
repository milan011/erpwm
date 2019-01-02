<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
// use App\GoodsPrice;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Package\PackageRepositoryInterface;
use App\Http\Resources\Package\PackageResource;
use App\Http\Resources\Package\PackageResourceCollection;
//use App\Http\Requests\Package\UpdatePackageRequest;
//use App\Http\Requests\Package\StorePackageRequest;

class PackageController extends Controller
{
    protected $package;

    public function __construct(

        PackageRepositoryInterface $package
    ) {
    
        $this->package = $package;
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

        $packages = $this->package->getAllPackage();

        // dd($packages);
        return new PackageResource($packages);
    }

    /**
     * 所有套餐列表(无分页)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function packageAll(Request $request)
    {
        $packages = $this->package->getPackages();

        return new PackageResource($packages);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.package.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $packageRequest)
    {
        // dd($packageRequest->all());
        if($this->package->isRepeat($packageRequest)){
            return $this->baseFailed($message = '该套餐已存在');
        }

        $new_package = $this->package->create($packageRequest);

        if($new_package){ //添加成功
            return $this->baseSucceed($respond_data = $new_package, $message = '添加成功');
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
    public function getPackage($id)
    {
        $package = $this->package->find($id);
        $package->hasManyPackageInfo;

        return $this->baseSucceed($respond_data = $package, $message = '');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $package      = $this->package->find($id); //套餐详情
        $package_info = $package->hasManyPackageInfo->toJson(); //套餐返还详情

        // dd($package);
        // dd($package_info);
        return view('admin.package.edit', compact('package', 'package_info'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $packageRequest, $id)
    {
        // dd($packageRequest->all());
        $update_package = $this->package->isRepeat($packageRequest);
        if($update_package && ($update_package->id != $id)){
            return $this->baseFailed($message = '您修改后的套餐信息与现有套餐冲突');
        }
        $package = $this->package->update($packageRequest, $id);
        // dd(redirect()->route('package.index'));
        return $this->baseSucceed($respond_data = $package, $message = '修改成功');
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
        $this->package->destroy($id);        
        return $this->baseSucceed($message = '修改成功');
    }

    //ajax判断车型是否重复
    public function checkRepeat(Request $request){

        // dd($request->all());
        if($this->category->isRepeat($request)){
            //车型重复
            return response()->json(array(
                'status' => 1,
                // 'data'   => $category,
                'message'   => '系列名称重复'
            ));
        }else{
            //车型不重复
            return response()->json(array(
                'status' => 0,
                'message'   => '系列名称不重复'
            ));
        }
    }
}
