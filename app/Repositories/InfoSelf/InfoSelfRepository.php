<?php
namespace App\Repositories\InfoSelf;

use App\InfoSelf;
use App\InfoDianxin;
use App\Manager;
use App\Package;

use Session;
use Illuminate\Http\Request;
use Gate;
use Datatables;
use Planbon;
use PHPZen\LaravelRbac\Traits\Rbac;
use Auth;
use Illuminate\Support\Facades\Input;
use DB;
use Debugbar;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\InfoSelf\InfoSelfRepositoryInterface;

class InfoSelfRepository implements InfoSelfRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['code', 'id', 'name', 'user_telephone', 'side_uim_number', 'side_uim_number_num','old_bind', 'manage_name', 'manage_telephone', 'manage_id', 'project_name', 'new_telephone', 'uim_number',  'is_jituan','side_number','side_number_num', 'netin', 'collections', 'balance_month', 'collections_type', 'creater_id', 'package_month', 'package_id', 'status','remark','created_at'];


    // 根据ID获得信息
    public function find($id)
    {
        return InfoSelf::select($this->select_columns)
                   ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getAllInfos($queryList)
    {   
        // dd($request->Plan_launch);
        // $query = Plan::query();  // 返回的是一个 QueryBuilder 实例
        $query = new InfoSelf();       // 返回的是一个Plan实例,两种方法均可
        // dd($request->all());
        $query = $query->addCondition($queryList); //根据条件组合语句
        // dd($request->pay_status);
        
        /*if(isset($request->payed)){
            if($request->payed){
                //已经付款
                $query = $query->where('status', '3');
                $query = $query->where('status','!=', '0');
            }else{
                //未付款
                $query = $query->whereIn('status', ['1','2']);
                $query = $query->where('status','!=', '0');
                $query = $query->where('old_bind', '0');
            }
        }*/
        // dd($query);
        // $query = $query->chacneLaunch($request->Plan_launch);
        /*Post::with(array('user'=>function($query){
            $query->select('id','username');
        }))->get();*/
        if($queryList['withNoPage']){ //无分页,全部返还

            $infos = $query->with('hasOnePackage', 'belongsToCreater', 'hasManyInfoDianxin')
                     ->select($this->select_columns)
                     ->orderBy('created_at', 'DESC')
                     ->where('status','!=', '0')
                     ->get();
        }else{

            $infos = $query->with('hasOnePackage', 'hasManyInfoDianxin')
                     ->with(['belongsToCreater'=>function($query){
                        $query->select('id','nick_name');
                     }])
                     ->select($this->select_columns)
                     ->where('status','!=', '0')
                     ->orderBy('created_at', 'DESC')
                     ->paginate(10);
        }

        /*foreach ($infos as $key => $value) {
            if (!empty($value->side_number)){
                // dd($value->side_number);
                $side_number = explode("|",  $value->side_number);
                dd(count($side_number));
                $vaule->side_numbers = count($side_number);
            }else{
                $vaule->side_numbers = '0';
            }
        }*/

        return $infos;
    }


    // 获取业务员录入信息
    public function getSalesmanInfo($creater_id, $netin)
    {   
        // dd($request->Plan_launch);
        // $query = Plan::query();  // 返回的是一个 QueryBuilder 实例
        $query = new InfoSelf();       // 返回的是一个Plan实例,两种方法均可

        return $query->select($this->select_columns)
                     ->where('creater_id', $creater_id)
                     ->where('netin', $netin)
                     ->where('status', '!=', '0')
                     ->get();
    }

    // 创建信息
    public function create($requestData)
    {             
        $requestData['creater_id']  = Auth::id();
        $requestData['remark']      = empty($requestData['remark']) ? '' : $requestData['remark'] ;

        $info   = new InfoSelf();
        //获得客户经理信息
        $manager = Manager::findOrFail($requestData['manage_id']);
        // dd($manager);
        // dd($requestData->all());
        $package = Package::findOrFail($requestData['package_id']);
        
        // dd($manager);
        // 处理副卡信息
        // dd($requestData->all());
        $side_list_info      = [];
        $side_number_arr     = [];
        $side_uim_number_arr = [];
        $side_list           = [];
        $side_number         = '';
        $side_uim_number     = '';

        // dd(array_filter($requestData['side_numbers']));
        // dd(array_filter($requestData['side_numbers']));
        // dd(empty(array_filter($requestData['side_numbers'])));
        if(!empty(array_filter($requestData['side_numbers']))){
            foreach ($requestData['side_numbers'] as $key => $value) {
                $side_list_info[$key]['side'] = $value['side_number'];
                $side_list_info[$key]['uim']  = $value['uim'];
            }
            // dd(array_filter($side_list_info));
            $side_list = a_array_unique($side_list_info);
            // dd($side_list);
            foreach ($side_list as $key => $value) {
                if($value['side'] !== null){
                    $side_number_arr[]     = $value['side'];
                    $side_uim_number_arr[] = $value['uim'];
                }         
            }
            // dd($side_number_arr);
            $side_number     = implode("|",  $side_number_arr);
            $side_uim_number = implode("|",  $side_uim_number_arr);
        }

        $side_uim_number_num = count(array_unique(array_filter($side_uim_number_arr)));

        /*p($side_list);
        p($side_uim_number);
        p($side_number);
        p($side_uim_number_arr);
        p($side_number_arr);
        p(count($side_number_arr));
        dd($side_uim_number_num);
        dd(explode('|', $side_uim_number));*/
        
        // 副卡uim数量
        
        
        // p(count($side_list));
        /*p($side_number);exit;
        p($side_uim_number);
        dd($side_list);
        dd($side_list_info);*/
        
        /*if (!empty($requestData['side_numbers'])){
            $side_number     = implode("|",  array_unique($requestData['side_numbers']));
            $side_number_num = count($requestData['side_numbers']);
        }else{
            $side_number_num = 0;
        }
            
        //处理副卡uim码
        if (!empty($requestData['side_uim_numbers'])){
            $side_uim_number     = implode("|",  array_unique($requestData['side_uim_numbers']));
            $side_uim_number_num = count($requestData['side_uim_numbers']);
        }else{
            $side_uim_number_num = 0;
        }*/
        // dd($side_number);
        $requestData['code']                 = getInfoCode();
        $requestData['manage_name']          = $manager->name;
        $requestData['manage_id']            = $manager->id;
        $requestData['manage_telephone']     = $manager->telephone;
        $requestData['package_month']        = $package->month_nums;
        $requestData['package_id']           = $package->id;
        $requestData['user_telephone']       = $requestData['user_telephone'];
        $requestData['side_number']          = $side_number;
        $requestData['side_number_num']      = count($side_number_arr);
        $requestData['side_uim_number']      = $side_uim_number;
        $requestData['side_uim_number_num']  = $side_uim_number_num;
        $requestData['status']               = '1';
        $requestData['netin']                = $requestData['netin_year'].'-'.$requestData['netin_month'];
        $requestData['old_bind']             = ($requestData['old_bind']) ? '1' : '0';
        $requestData['is_jituan']            = ($requestData['is_jituan']) ? '1' : '0';

        // dd($requestData->all());

        $input  =  array_replace($requestData->all());
        
        $info->fill($input);

        $info = $info->create($input);
        

        return $info;     
    }

    // 信息更新
    public function update($requestData, $id)
    {   
        // dd($requestData->all());
        $info   = InfoSelf::select($this->select_columns)->findorFail($id); //获取信息
        $manager = Manager::findOrFail($requestData['manage_id']);//获得客户经理信息
        $package = Package::findOrFail($requestData['package_id']);//获得客户经理信息
        // dd($manager);
        // 处理副卡信息
        // dd($requestData->all());
        $side_list_info      = [];
        $side_number_arr     = [];
        $side_uim_number_arr = [];
        $side_list           = [];
        $side_number         = '';
        $side_uim_number     = '';

        // dd(array_filter($requestData['side_numbers']));
        // dd(array_filter($requestData['side_numbers']));
        // dd(empty(array_filter($requestData['side_numbers'])));
        if(!empty(array_filter($requestData['side_numbers']))){
            foreach ($requestData['side_numbers'] as $key => $value) {
                $side_list_info[$key]['side'] = $value['side_number'];
                $side_list_info[$key]['uim']  = $value['uim'];
            }
            // dd(array_filter($side_list_info));
            $side_list = a_array_unique($side_list_info);
            // dd($side_list);
            foreach ($side_list as $key => $value) {
                if($value['side'] !== null){
                    $side_number_arr[]     = $value['side'];
                    $side_uim_number_arr[] = $value['uim'];
                }         
            }
            // dd($side_number_arr);
            $side_number     = implode("|",  $side_number_arr);
            $side_uim_number = implode("|",  $side_uim_number_arr);
        }

        $side_uim_number_num = count(array_unique(array_filter($side_uim_number_arr)));
        
        $info->name                 = $requestData->name;
        $info->user_telephone       = $requestData->user_telephone;
        $info->manage_name          = $manager->name;
        $info->manage_telephone     = $manager->telephone;
        $info->manage_id            = $manager->id;
        $info->package_id           = $package->id;
        $info->package_month        = $package->month_nums;
        $info->project_name         = $requestData->project_name;
        $info->side_number_num      = count($side_number_arr);
        $info->uim_number           = $requestData->uim_number;
        $info->collections          = $requestData->collections;
        $info->side_number          = $side_number;
        $info->side_uim_number      = $side_uim_number;
        $info->remark               = $requestData->remark;
        $info->side_uim_number_num  = $side_uim_number_num;
        $info->collections_type     = $requestData->collections_type;
        $info->netin                = $requestData->netin_year.'-'.$requestData->netin_month;
        $info->old_bind             = ($requestData->old_bind) ? '1' : '0';
        $info->is_jituan            = ($requestData->is_jituan) ? '1' : '0';

        $info->save();

        return $info;                     
    }

    // 删除信息
    public function destroy($id)
    {
        try {
            $info = InfoSelf::findorFail($id);
            $info->status = '0';
            $info->save();

            return $info;
           
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }       
    }

    //判断电话号码是否重复
    public function isRepeat($new_telephone){

        $info = InfoSelf::where('new_telephone', $new_telephone)->where('status', '!=', '0')->first();

        return $info;

    }

    //处理信息
    public function infoDeal($queryList){

        $infoSelfs_not_payed = $this->getAllInfos($queryList); //尚未返还完成信息
        $info_chunk          = $infoSelfs_not_payed->chunk(1000);
        $info_deal_nums      = 0;
        $info_payed_nums     = 0; //有多少号码已完成返款

        // dd($info_chunk);

        foreach ($info_chunk as $key => $value) {
            # 匹对所有未返还完成信息,若电信信息有返还,则设置电信信息infi_self_id及status
            foreach ($value as $k => $v) {
                $info_dianx = $this->infoDealed($v->new_telephone);

                if($info_dianx->count() == 0){
                    //没有返款信息
                    // dd($v);
                    if(!empty($v->side_number)){ //副卡是否返款
                        $side_list = explode("|", $v->side_number);
                        /*dd($v);
                        dd($side_list);*/
                        foreach ($side_list as $s_key => $s_value) {
                            $fuka_info = $this->infoDealed($s_value);

                            if($fuka_info->count() != 0){
                                //有返还信息
                                /*p($v->id);
                                dd($fuka_info);*/
                                foreach ($fuka_info as $f_key => $f_value) {
                                    
                                    /*$f_value->status = '2';
                                    $f_value->info_self_id = $v->id;*/
                                    $f_value = $this->dealSelfAndDianxin($f_value, $v);
                                    // $f_value->save();
                                    // dd($f_value);
                                    $info_deal_nums++;
                                }
                            }
                        }
                    }
                }else{
                    // 有返还信息
                    foreach ($info_dianx as $info_key => $info_value) {
                        # code...
                        /*p($v->id);
                        dd($info_value);*/
                        /*$info_value->status = '2';
                        $info_value->info_self_id = $v->id;*/
                        $info_value = $this->dealSelfAndDianxin($info_value, $v);

                        // $info_value->save();
                        $info_deal_nums++;
                    }
                }
            }
        }

        // dd($info_deal_nums);
        // Session::flash('sucess', '信息处理成功,共处理'.$info_deal_nums.'条信息');
        // $be = time();
        if($info_deal_nums == 0){

            $info_payed_nums = $this->infoPayed();
        }
        // $af = time();

        // dd($af - $be);
        // dd($info_payed_nums);

        return $info_deal_nums;
    }

    //信息是否被匹对
    public function infoDealed($return_telephone){

        // dd($info->side_number);

        $infoDealed = InfoDianxin::select(['id', 'info_self_id', 'status'])
                                   ->where('return_telephone', $return_telephone)
                                   ->where('status', '1')
                                   ->get();

        /*if(!empty($info->side_number)){ //副卡是否返款
            $side_list = explode("|", $info->side_number);
            foreach ($side_list as $key => $value) {
                $infoDealed = InfoDianxin::select(['id', 'info_self_id', 'status'])
                                   ->where('return_telephone', $value)
                                   ->where('status', '1')
                                   ->get();
            }
        }*/
        // dd(lastSql());
        /*dd($infoDealed->count());
        dd(empty($infoDealed->count()));*/
        return $infoDealed;
    }

    // 处理匹对成功信息
    public function dealSelfAndDianxin($infoDianxin, $infoSelf)
    {   
        // dd($infoDianxin);
        DB::transaction(function() use ($infoDianxin, $infoSelf){
            // 处理匹对的电信信息及录入信息
            $infoDianxin->info_self_id = $infoSelf->id;
            $infoDianxin->status       = '2';

            if($infoSelf->status == '1'){
                //第一次被返还信息
                $infoSelf->status = '2';
                $infoSelf->save();
            }

            $infoDianxin->save();

            return true;
        });
    }

    //已返还完成信息处理
    protected function infoPayed(){

        // dd($request->Plan_launch);
        // $query = Plan::query();  // 返回的是一个 QueryBuilder 实例
        $query = new InfoSelf();       // 返回的是一个Plan实例,两种方法均可
        $count = 0;

        $un_payed = $query->with('hasManyInfoDianxin')
                          ->select($this->select_columns)
                          ->where('status', '2')
                          ->orderBy('created_at', 'DESC')
                          ->get();
        // dd($un_payed);
        $unPayedInfos = $un_payed->chunk(500);
        foreach ($unPayedInfos as $key => $value) {
            # code...
            // dd($value);
            // dd($v->hasManyInfoDianxin->count());
            //p($v->package_month);
            //dd($v->hasManyInfoDianxin->count());
            // dd($v);
            foreach ($value as $k => $v) {
                # code...
                $package_months = $v->package_month;
                $return_months  = $v->hasManyInfoDianxin->count();
                // $return_months  = '2';
                
                if($package_months == $return_months){
                    //已返还月符合套餐返还月数
                    $v->status = '3';
                    $v->save();
                    $count++;
                    // array_push($count, $v->id);
                }
            }
        }
        // dd($count);
        // dd($un_payed[0]->hasManyInfoDianxin()->count());
        return $count;
    }

    //约车状态转换，暂时只有激活-废弃转换
    public function statusChange($requestData, $id){

        // dd($requestData->all());
        DB::transaction(function() use ($requestData, $id){

            $Plan         = Plan::select($this->select_columns)->findorFail($id); //约车对象
            $follow_info = new PlanFollow(); //约车跟进对象

            if($requestData->status == 1){

                $update_content = collect([Auth::user()->nick_name.'激活约车'])->toJson();
            }else{

                $update_content = collect([Auth::user()->nick_name.'废弃约车'])->toJson();
            }
            

            // 约车编辑信息
            $Plan->Plan_status = $requestData->status;

            // 约车跟进信息
            $follow_info->Plan_id       = $id;
            $follow_info->user_id      = Auth::id();
            $follow_info->follow_type  = '1';
            $follow_info->operate_type = '2';
            $follow_info->description  = collect($update_content)->toJson();
            $follow_info->prev_update  = $Plan->updated_at;
         
            $follow_info->save();
            $Plan->save(); 

            return $Plan;
        });
    }
}
