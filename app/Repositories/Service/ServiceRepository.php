<?php
namespace App\Repositories\Service;

use App\Service;
use Session;
use Illuminate\Http\Request;
use Gate;
use Datatables;
use Carbon;
use PHPZen\LaravelRbac\Traits\Rbac;
use Auth;
use Illuminate\Support\Facades\Input;
use DB;
use Debugbar;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\Service\ServiceRepositoryInterface;

class ServiceRepository implements ServiceRepositoryInterface
{

    //默认查询数据
    protected $select_columns = ['id','name', 'type', 'status', 'return_price', 'return_ratio', 'remark', 'creater_id', 'created_at', 'updated_at'];

    // 根据ID获得业务信息
    public function find($id)
    {
        return Service::select($this->select_columns)
                       ->with('belongsToCreater')
                       ->findOrFail($id);
    }

    // 获得业务列表
    public function getAllService()
    {   
        return Service::where('status', '1')->with('belongsToCreater')->orderBy('created_at', 'DESC')->paginate(10);
    }

    // 获得所有业务
    public function getServices()
    {   
        return Service::select($this->select_columns)
                       ->where('status', '1')
                       ->get();
    }

    // 创建业务
    public function create($requestData)
    {   

        $requestData['creater_id']    = Auth::id();
        $requestData['status']        = '1';
        //$requestData['name']          = $requestData['service_name'];
        // dd($requestData->all());
        
        $service = new Service();
        $input =  array_replace($requestData->all());
        $service->fill($input);
        $newService = $service->create($input);
        return $newService;
    }

    // 修改业务
    public function update($requestData, $id)
    {   
        // dd($requestData->all());
        $service  = Service::findorFail($id);
        $input    =  array_replace($requestData->all());
        // dd($service);
        // dd($service->hasManyServiceInfo);
        $service->fill($input)->save();

        return $service;
    }

    // 删除业务
    public function destroy($id)
    {
        try {
            $service = Service::findorFail($id);
            $service->status = '0';
            $service->save();

            return $service;
           
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }      
    }

    //判断业务是否重复
    public function isRepeat($requestData){

        $service = Service::select('id', 'name')
                        ->where('name', $requestData->name)
                        ->where('status', '1')
                        ->first();
        // dd(isset($cate));
        return $service;
    }
}
