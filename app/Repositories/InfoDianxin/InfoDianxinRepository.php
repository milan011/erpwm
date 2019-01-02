<?php
namespace App\Repositories\InfoDianxin;

use App\InfoDianxin;
//use App\Area;
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
use App\Repositories\InfoDianxin\InfoDianxinRepositoryInterface;

class InfoDianxinRepository implements InfoDianxinRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id','code', 'name', 'jiakuan', 'manager', 'info_self_id', 'jituan','refunds', 'balance_month', 'return_telephone','yongjin', 'netin', 'creater_id', 'status', 'remark','created_at'];

    // 根据ID获得车源信息
    public function find($id)
    {
        return InfoDianxin::select($this->select_columns)
                   ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getAllDianXinInfos($queryList)
    {   
        // dd($request->all());
        // $query = Order::query();  // 返回的是一个 QueryBuilder 实例
        $query = new InfoDianxin();       // 返回的是一个Order实例,两种方法均可

        $query = $query->addCondition($queryList); //根据条件组合语句
     
        if(isset($request->dealed)){
            if(!$request->dealed){

                $query = $query->where('status', '1');
            }
        }

        $query = $query->where('status','!=', '0');
        $query = $query->with('belongsToCreater', 'belongsToInfoSelf');
        $query = $query->select($this->select_columns)->orderBy('created_at', 'desc')->orderBy('id', 'desc');

        // $query = $query->where('car_status', $request->input('car_status', '1'));

        if($queryList['withNoPage']){ //无分页,全部返还
            return $query->get();
        }else{
            return $query->paginate(10);
        }
    }

    // 导入信息
    public function createImport($info)
    {   
        
        $infoDianxin = new InfoDianxin();

        $info['creater_id'] = Auth::id();
        $info['status']     = '1';

        $input =  array_replace($info);
        
        // dd($input);
        $info = $infoDianxin->create($input);
        
        return $info;     
    }
    // 创建信息
    public function create($requestData)
    {   
        // dd($requestData->all());
        /*if(!empty($requestData->add_shoudong)){
            //手动添加电信信息
            $repeated = $this->isRepeat($requestData->return_telephone,$requestData->balance_month);

            if(null !== $repeated){
                Session::flash('sucess', '该号码已经存在');
                return $repeated;
            }

            $requestData['netin']      = $requestData['netin_year'].'-'.$requestData['netin_moth'];

            unset($requestData['_token']);
            unset($requestData['netin_year']);
            unset($requestData['netin_moth']);
        }*/
        
        // dd($requestData);

        $infoDianxin = new InfoDianxin();

        $requestData['creater_id'] = Auth::id();
        $requestData['status']     = '1';
        $requestData['netin']      = $requestData['netin_year'].$requestData['netin_month'];

        unset($requestData['token']);
        unset($requestData['netin_year']);
        unset($requestData['netin_month']);
        $input =  array_replace($requestData->all());
        unset($input['token']);
        
        // dd($input);
        // $info = $infoDianxin->insertIgnore($input);
        // dd(lastSql());
        // dd($info);
        // $info = $infoDianxin->create($input);
        
        try {
            $info = $infoDianxin->create($input);        
        } catch (\Illuminate\Database\QueryException $e) {
            // return false;
            // dd($e->getMessage());
            $info = InfoDianxin::select($this->select_columns)
                               ->where('return_telephone', $requestData->return_telephone)
                               ->where('balance_month', $requestData->balance_month)
                               ->first();

            if($info->status == '0'){
                $info->name            = $requestData->name;
                $info->yongjin         = $requestData->yongjin;
                $info->refunds         = $requestData->refunds;
                $info->jiakuan         = $requestData->jiakuan;
                $info->jituan          = $requestData->jituan;
                $info->manager         = $requestData->manager;
                $info->balance_month   = $requestData->balance_month;
                $info->netin           = $requestData->netin;
                $info->status          = '1';

                // dd($info);
                $info->save();

                return $info;
            }
        }

        return $info;     
    }

    // 信息更新
    public function update($requestData, $id)
    {   

        $info   = InfoDianxin::select($this->select_columns)->findorFail($id); //获取信息
        
        $info->name            = $requestData->name;
        $info->yongjin         = $requestData->yongjin;
        $info->refunds         = $requestData->refunds;
        $info->jiakuan         = $requestData->jiakuan;
        $info->jituan          = $requestData->jituan;
        $info->manager         = $requestData->manager;
        $info->balance_month   = $requestData->balance_month;
        $info->netin           = $requestData->netin_year.$requestData->netin_month;

        // Session::flash('sucess', '信息修改成功');
        $info->save();

        return $info;                     
    }

    // 删除信息
    public function destroy($id)
    {
        try {
            $info = InfoDianxin::findorFail($id);
            $info->status = '0';
            $info->save();
            
            return $info;
           
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }      
    } 

    //判断电话号码是否重复
    public function isRepeat($return_telephone, $balance_month){

        $info = InfoDianxin::where('return_telephone', $return_telephone)
                        ->where('status', '!=', '0')
                        ->where('balance_month', $balance_month)
                        ->first();
        /*dd(lastSql());
        dd($info);*/
        return $info;

    }
}
