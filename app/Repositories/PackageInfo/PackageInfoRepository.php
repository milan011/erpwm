<?php
namespace App\Repositories\PackageInfo;

use App\PackageInfo;
use Session;
use Illuminate\Http\Request;
use Gate;
use Datatables;
use Carbon;
use Auth;
use Illuminate\Support\Facades\Input;
use DB;
use Debugbar;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\PackageInfo\PackageInfoRepositoryInterface;

class PackageInfoRepository implements PackageInfoRepositoryInterface
{

    //默认查询数据
    protected $select_columns = ['id', 'bloc', 'name', 'package_price',  'netin', 'status', 'recommend', 'remark', 'creater_id', 'created_at', 'updated_at'];

    // 根据ID获得车型信息
    public function find($id)
    {
        return Package::select($this->select_columns)
                       ->findOrFail($id);
    }

    // 获得套餐列表(分页)
    public function getAllPackageInfo()
    {   
        return Package::where('status', '1')->orderBy('created_at', 'DESC')->paginate(10);
    }

    

    // 创建车型
    public function create($requestData)
    {   
        // $requestData['user_id'] = Auth::id();
        // dd($requestData->all());
        $package = new Package();
        // $input =  array_replace($requestData->all());

        
        $input['name']       = $requestData->name;
        $input['status']     = $requestData->status;
        $input['creater_id'] = Auth::id();
        // dd($input);

        $package = $package->insertIgnore($input);

        Session::flash('sucess', '添加成功');
        return $package;
    }

    // 修改车型
    public function update($requestData, $id)
    {
        
        $package  = Package::findorFail($id);
        $input =  array_replace($requestData->all());
        // dd($package->fill($input));
        $package->fill($input)->save();
        // dd($Package->toJson());
        Session::flash('sucess', '修改车型成功');
        return $package;
    }

    // 删除车型
    public function destroy($id)
    {
        try {
            $package = Package::findorFail($id);
            $package->status = '0';
            $package->save();
            Session::flash('sucess', '删除成功');
           
        } catch (\Illuminate\Database\QueryException $e) {
            Session()->flash('faill', '删除失败');
        }      
    }

    //判断车型是否重复
    public function isRepeat($requestData){

        $cate = Package::select('id', 'name')
                        ->where('name', $requestData->name)
                        ->first();
        // dd(isset($cate));
        return isset($cate);
    }
}
