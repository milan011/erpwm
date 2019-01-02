<?php
namespace App\Repositories\Manager;

use App\Manager;
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
use App\Repositories\Manager\ManagerRepositoryInterface;

class ManagerRepository implements ManagerRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id', 'name', 'telephone', 'first_letter', 'creater_id', 'status', 'created_at'];

    // 根据ID获得车源信息
    public function find($id)
    {
        return Manager::select($this->select_columns)
                   ->findOrFail($id);
    }

    // 根据不同参数获得客户经理
    public function getAllManagers($request)
    {   
        // dd($request->all());
        // $query = Order::query();  // 返回的是一个 QueryBuilder 实例
        $query = new Manager();       // 返回的是一个Order实例,两种方法均可

        $query = $query->addCondition($request->all()); //根据条件组合语句
     
        // dd($query);
        // $query = $query->where('is_show', '1');
        $query = $query->where('status', '1');
        $query = $query->where('name', '!=', '');
        // $query = $query->where('car_status', $request->input('car_status', '1'));

        return $query->select($this->select_columns)
                     ->orderBy('first_letter')
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);
    }

    // 获取所有客户经理
    public function getManagers()
    {   
        // dd($request->all());
        // $query = Order::query();  // 返回的是一个 QueryBuilder 实例
        $query = new Manager();       // 返回的是一个Order实例,两种方法均可

        $query = $query->where('status', '1');
        $query = $query->where('name', '!=', '');

        return $query->select($this->select_columns)
                     ->orderBy('first_letter')
                     ->orderBy('created_at', 'desc')
                     ->get();
    }

    // 添加客户经理
    public function create($requestData)
    {   
        $requestData['creater_id'] = Auth::id();
        // dd($requestData->all());
        $requestData['first_letter'] = strtoupper($requestData['first_letter']);
        $manager = new Manager;
        $input  =  array_replace($requestData->all());
        $manager->fill($input);

        $manager = $manager->create($input);

        return $manager;         
    }

    // 修改商品
    public function update($requestData, $id)
    {
        
        $manager  = Manager::findorFail($id);
        
        $manager->name          = $requestData->name;
        $manager->telephone     = $requestData->telephone;
        $manager->first_letter  = $requestData->first_letter;
        //$manager->wx_number     = $requestData->wx_number;
        //$manager->email         = $requestData->email;
        //$manager->address       = $requestData->address;
        //$manager->remark        = $requestData->remark;

        $manager->save();
        // dd($shop->toJson());
        // Session::flash('sucess', '修改成功');
        return $manager;
    }

    // 删除商品
    public function destroy($id)
    {
        try {
            $manager = Manager::findorFail($id);
            $manager->status = '0';
            $manager->save();
            
            return $manager;
           
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }      
    }

    //车源状态转换，暂时只有激活-废弃转换
    public function statusChange($requestData, $id){

        // dd($requestData->all());
        DB::transaction(function() use ($requestData, $id){

            $car         = Order::select($this->select_columns)->findorFail($id); //车源对象
            $follow_info = new CarFollow(); //车源跟进对象

            if($requestData->status == 0){
                
                // dd('not have sb');
                $update_content = collect([Auth::user()->nick_name.'激活车源'])->toJson();
                $car->car_status = '1';
            }else{

                $update_content = collect([Auth::user()->nick_name.'废弃车源'])->toJson();
                $car->car_status = '0';
            }          

            // 车源跟进信息
            $follow_info->car_id       = $id;
            $follow_info->user_id      = Auth::id();
            $follow_info->follow_type  = '1';
            $follow_info->operate_type = '2';
            $follow_info->description  = $update_content;
            $follow_info->prev_update  = $car->updated_at;
         
            $follow_info->save();
            $car->save(); 

            return $car;
        });
    }
}
