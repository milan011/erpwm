<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Auth;
use Gate;
//use Excel;
use DB;
use Session;
use Carbon;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\InfoSelf\InfoSelfRepositoryInterface;
use App\Repositories\InfoDianxin\InfoDianxinRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Manager\ManagerRepositoryInterface;
use App\Repositories\PackageInfo\PackageInfoRepositoryInterface;
use App\Repositories\Package\PackageRepositoryInterface;
use App\Http\Resources\InfoSelf\InfoSelfResource;
use App\Http\Resources\InfoSelf\InfoSelfResourceCollection;
/*use App\Http\Requests\InfoSelf\UpdateOrderRequest;
use App\Http\Requests\InfoSelf\StoreOrderRequest;*/

class InfoSelfController extends Controller
{   
    protected $infoSelf;

    public function __construct(

        InfoSelfRepositoryInterface $infoSelf,
        InfoDianxinRepositoryInterface $infoDianxin,
        ManagerRepositoryInterface $manager,
        PackageInfoRepositoryInterface $package,
        UserRepositoryInterface $user
    ) {
    
        $this->infoSelf    = $infoSelf;
        $this->infoDianxin = $infoDianxin;
        $this->manager     = $manager;
        $this->package     = $package;
        $this->user        = $user;
        // $this->middleware('brand.create', ['only' => ['create']]);
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query_list = jsonToArray($request->input('query')); //获取搜索信息

        // isset($query_list['withNoPage']) ? : false;

        //$query_list['withNoPage'] = ($query_list['withNoPage']) ? true : false ;

        $query_list['netin'] = $this->netinQuery($query_list['netin_year'], $query_list['netin_month']);

        // dd($query_list);

        /*if(empty($query['netin_year']) && empty($query['netin_month'])){

        }else{
            //有入网日期筛选
            $current_date  = Carbon::now(); //当前日期
            $current_year  = $current_date->year;  //当前年
            $current_month = $current_date->month; //当前月

            if(strlen($current_month) == 1){
                $current_month = '0'.$current_month;
            }

            if(empty($query['netin_year'])){
                // p('no year');
                $query['netin_year'] = (string)$current_year; 
            }

            if(empty($query['netin_month'])){
                // p('no month');
                $query['netin_month'] = (string)$current_month; 
            }
        }
        dd($query);*/

        //$request = $this->netin_date($request);

        // $query['pay_status'] = !empty($query['pay_status']) ? $query['pay_status'] : '' ;

        // dd($select_conditions);
        
        // dd(route('infoSelf.index'));
        /*if($request->isMethod('post')){

            $netin_year  = $request->netin_year; //入网年
            $netin_month = $request->netin_month; //入网月
            $netin  = $request->netin_year . '-' . $request->netin_month;
        }else{

            $dt = Carbon::now(); //当前日期

            $netin_year  = $dt->year;  //当前年
            $netin_month = $dt->month; //当前月

            $netin  = $netin_year . '-' . $netin_month;
            $request->nettin_year = $netin_year;
            $request->netin_month = $netin_month;
            // dd($netin_year);
        }*/

        // $request->netin = $netin;

        $infoSelfs = $this->infoSelf->getAllInfos($query_list);
        // dd(lastSql());
        // dd($infoSelfs[0]->belongsToCreater());
        // $lll = $infoSelfs[0]->belongsToCreater;
        /*dd($lll);
        dd(lastSql());*/
        return new InfoSelfResource($infoSelfs);
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function payed(Request $request)
    {
        $request = $this->netin_date($request);
        $info_status_now = '已付款';
        $select_conditions  = $request->all();
        $request['pay_status']   = 'payed';
        $pay_status = 'payed';
        // dd($select_conditions);
        $action = route('infoSelf.payed');

        /*if($request->isMethod('post')){

            $netin_year  = $request->netin_year; //入网年
            $netin_month = $request->netin_month; //入网月
            $netin  = $request->netin_year . '-' . $request->netin_month;
        }else{

            $dt = Carbon::now(); //当前日期

            $netin_year  = $dt->year;  //当前年
            $netin_month = $dt->month; //当前月

            $netin  = $netin_year . '-' . $netin_month;
            $request->nettin_year = $netin_year;
            $request->netin_month = $netin_month;
            // dd($netin_year);
        }*/

        // $request->netin = $netin;

        $infoSelfs = $this->infoSelf->getAllInfos($request);
        // dd($infoSelfs[0]->belongsToCreater);
        
        return view('admin.infoSelf.index', compact('infoSelfs','pay_status', 'action', 'info_status_now','select_conditions', 'netin', 'netin_year', 'netin_month'));
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function paying(Request $request)
    {
        $request = $this->netin_date($request);
        $select_conditions  = $request->all();
        $request['pay_status']   = 'paying';
        $pay_status = 'paying';
        $info_status_now = '付款中';
        $action = route('infoSelf.paying');
        // dd($select_conditions);
        

        /*if($request->isMethod('post')){

            $netin_year  = $request->netin_year; //入网年
            $netin_month = $request->netin_month; //入网月
            $netin  = $request->netin_year . '-' . $request->netin_month;
        }else{

            $dt = Carbon::now(); //当前日期

            $netin_year  = $dt->year;  //当前年
            $netin_month = $dt->month; //当前月

            $netin  = $netin_year . '-' . $netin_month;
            $request->nettin_year = $netin_year;
            $request->netin_month = $netin_month;
            // dd($netin_year);
        }*/

        // $request->netin = $netin;

        $infoSelfs = $this->infoSelf->getAllInfos($request);

        // dd($infoSelfs[0]->belongsToCreater);
        
        return view('admin.infoSelf.index', compact('infoSelfs','pay_status','action', 'info_status_now', 'select_conditions', 'netin', 'netin_year', 'netin_month'));
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function notPayed(Request $request)
    {
        $request = $this->netin_date($request);
        $select_conditions       = $request->all();
        $request['pay_status']   = 'unpayed';
        $pay_status = 'unpayed';
        $info_status_now = '未付款';
        $notPayed = true;
        $action = route('infoSelf.notPayed');
        // dd($select_conditions);
        // dd($action);

        // dd($request->all());
        $infoSelfs = $this->infoSelf->getAllInfos($request);

        // dd(lastSql());
        // dd($infoSelfs);
        // dd($infoSelfs[0]->belongsToCreater);
        
        return view('admin.infoSelf.index', compact('infoSelfs', 'pay_status','action', 'info_status_now','select_conditions', 'notPayed', 'netin', 'netin_year', 'netin_month'));
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getInfo($id)
    {
        $info = $this->infoSelf->find($id);
        $info->belongsToCreater;
        $info->hasOnePackage;
        $info->hasManyInfoDianxin;

        return $this->baseSucceed($respond_data = $info, $message = '');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $dt = Carbon::now(); //当前日期

        $dt_year  = $dt->year;  //当前年
        $dt_month = $dt->month; //当前月

        $managers = $this->manager->getManagers(); // 电信客户经理
        $packages = $this->package->getPackages(); // 电信客户经理

        return view('admin.infoSelf.create', compact('dt_year', 'dt_month', 'managers', 'packages'));
    }

    /**
     * 订单存储
     * 基本信息--商品信息
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        // dd($request->all());

        if($this->infoSelf->isRepeat($request->new_telephone)){
            return $this->baseFailed($message = '入网号码已存在');
        }

        $info = $this->infoSelf->create($request);
        $info->hasOnePackage;
        $info->belongsToCreater;

        if($info){ //添加成功
            return $this->baseSucceed($respond_data = $info, $message = '添加成功');
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
        $info         = array();
        $package_info = array();

        $info         = $this->infoSelf->find($id);
        $package_info = $info->hasOnePackage;

        if(!empty($info->side_number)){

            $side_number_array     = explode('|', $info->side_number); //副卡数组
            $side_uim_number_array = explode('|', $info->side_uim_number); //副卡uim数组
        }
        
         // dd($info->hasManyInfoDianxin);

        return view('admin.infoSelf.show', compact('info', 'package_info', 'side_number_array','side_uim_number_array'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $side_number_array = array();
        $info              = $this->infoSelf->find($id);
        
        // dd($info->side_number);

        $netin_date  = explode('-', $info->netin); //入网日期转数组
        $netin_year  = $netin_date[0]; //入网年
        $netin_month = $netin_date[1]; //入网月

        if(!empty($info->side_number)){

            $side_number_array     = explode('|', $info->side_number); //副卡数组
            $side_uim_number_array = explode('|', $info->side_uim_number); //副卡uim数组
        }
        
        // dd($side_number_array);
        

        $managers = $this->manager->getManagers(); // 电信客户经理
        $packages = $this->package->getPackages(); // 套餐信息


        return view('admin.infoSelf.edit', compact(
            'info', 'managers', 'packages', 'netin_year', 'netin_month', 'side_number_array','side_uim_number_array'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        // dd($request->all());

        $info = $this->infoSelf->update($request, $id);
        $info->hasOnePackage;

        return $this->baseSucceed($respond_data = $info, $message = '修改成功');
    }

    /**
     * Remove the specified resource from storage.
     * 删除订单
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($id);
        $this->infoSelf->destroy($id);
        return $this->baseSucceed($message = '修改成功');
    }

    /**
     * 信息处理
     * 基本信息--商品信息
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dealWith(Request $request)
    {
        /**
        * 获取所有未返还完成信息,与电信导入表数据对比并处理
        * 处理结果反馈至返还记录
        * 若返还完成,则将信息状态设置为完成状态
        * 
        */
   
        // 获取全部尚未返还完成信息
        $request['payed']        = false;
        $request['withNoPage']   = true; //获取全部数据
        $notPayed = true;
        // dd($select_conditions);
        $infoSelfs_not_payed = $this->infoSelf->infoDeal($request); //尚未返还完成信息

        // dd($infoSelfs_not_payed);

        //处理已经返还完成信息
        $infoSelfs_payed = $this->infoSelf->infopayed($request); //尚未返还完成信息

        return redirect('infoSelf/notPayed')->withInput();
    }

    /**
     * 信息统计
     * 基本信息--商品信息
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function statistics(Request $request)
    {
        
        $users = $this->user->getusers();
        // dd($users);
        // dd($users);$user->hasRole('writer');

        $salesmans = $users->filter(function ($value, $key) { //只获取销售顾问

            return $value->hasRole('saler');
        });       
        // $salesmans = DB::table('zr_users')->where('status', '1')->where('id','!=', '1')->get();
        // dd($salesmans);
        foreach ($salesmans as $key => $value) {
            # 每个业务员统计
            $salesman_list[$key-1]['id'] = $value->id;
            $salesman_list[$key-1]['nick_name'] = $value->nick_name;
        }

        $query_list = jsonToArray($request->input('query')); //获取搜索信息

        // isset($query_list['withNoPage']) ? : false;

        //$query_list['withNoPage'] = ($query_list['withNoPage']) ? true : false ;

        $netin = $this->netinQuery($query_list['netin_year'], $query_list['netin_month']);
        // dd($salesman_list);
        // $netin = '2018-07';
        foreach ($salesman_list as $key => $value) {
            # 每个业务员统计
            $salesman_info      = $this->infoSelf->getSalesmanInfo($value['id'], $netin);
            $side_number_num    = 0; //未绑老卡信息数
            $side_number_old    = 0; //绑老卡信息数
            $old_bind_number    = 0; //绑老卡信息数
            // dd(lastSql());
            // dd($value);
            // dd($salesman_info );
            foreach ($salesman_info as $k => $v) {
                # 统计业务员副卡数目
                
                if($v->old_bind == 1){
                    //绑老卡
                    if(!empty($v->side_number)){

                        $side_array_old = explode("|", $v->side_number);
                        // p(count($side_array));

                        $side_number_old += count($side_array_old);
                    }
                    $old_bind_number++;
                }else{
                    //不绑老卡
                    if(!empty($v->side_number)){

                        $side_array = explode("|", $v->side_number);
                        // p(count($side_array));

                        $side_number_num += count($side_array);
                    }
                }
                
            }
            /*p($side_number);
            dd($salesman_info->count());*/

            $salesman_statistics[$key]['nick_name']         = $value['nick_name'];
            $salesman_statistics[$key]['info_nums_all']     = $salesman_info->count();
            $salesman_statistics[$key]['info_nums_old']     = $old_bind_number;
            $salesman_statistics[$key]['info_nums_num']     = ($salesman_info->count() - $old_bind_number);
            $salesman_statistics[$key]['side_nums_all']     = $side_number_num + $side_number_old;
            $salesman_statistics[$key]['side_nums_old']     = $side_number_old;
            $salesman_statistics[$key]['side_nums_num']     = $side_number_num;
            $salesman_statistics[$key]['netin']             = $netin;
        }

        // dd($salesman_statistics);

        $total_num = [
            'info_nums_all_total'  => 0,
            'info_nums_old_total'  => 0,
            'info_nums_num_total'  => 0,
            'side_nums_all_total'  => 0,
            'side_nums_old_total'  => 0,
            'side_nums_num_total'  => 0,
            'total_all'  => 0,

        ];

        foreach ($salesman_statistics as $key => $value) {
            $total_num['info_nums_all_total'] += $value['info_nums_old'] + $value['info_nums_num'];
            $total_num['info_nums_old_total'] += $value['info_nums_old'];
            $total_num['info_nums_num_total'] += $value['info_nums_num'];
            $total_num['side_nums_all_total'] += $value['side_nums_old'] + $value['side_nums_num'];
            $total_num['side_nums_old_total'] += $value['side_nums_old'];
            $total_num['side_nums_num_total'] += $value['side_nums_num'];
            $total_num['total_all'] += $value['info_nums_all'] + $value['side_nums_all'];
        }

        // dd($salesman_statistics);
        $total_arr = [
            'nick_name'     => '总计',
            'info_nums_all' => $total_num['info_nums_all_total'],
            'info_nums_num' => $total_num['info_nums_num_total'],
            'info_nums_old' => $total_num['info_nums_old_total'],
            'side_nums_all' => $total_num['side_nums_all_total'],
            'side_nums_num' => $total_num['side_nums_num_total'],
            'side_nums_old' => $total_num['side_nums_old_total'],
            'netin'         => $netin
        ];

        array_push($salesman_statistics, $total_arr);

        return $salesman_statistics;
        /*return response([
                'data' => $salesman_statistics
            ]); */
        
        //return view('admin.infoSelf.statistics', compact('salesman_statistics', 'netin', 'netin_year', 'netin_month', 'total_num'));
    }

    //处理入网日期格式
    protected function netinQuery($netin_year, $netin_month){

        if(empty($netin_year) && empty($netin_month)){
            $netin = '';
        }else{
            //有入网日期筛选
            $current_date  = Carbon::now(); //当前日期
            $current_year  = $current_date->year;  //当前年
            $current_month = $current_date->month; //当前月

            if(strlen($current_month) == 1){
                $current_month = '0'.$current_month;
            }

            if(empty($netin_year)){
                // p('no year');
                $netin_year = (string)$current_year; 
            }

            if(empty($netin_month)){
                // p('no month');
                $netin_month = (string)$current_month; 
            }

            $netin = $netin_year . '-' . $netin_month;
        }

        return $netin;
    }

    protected function netin_date($request){

        if($request->isMethod('post')){
            // p('post');
            $netin_year  = $request->netin_year; //入网年
            $netin_month = $request->netin_month; //入网月
            $netin  = $request->netin_year . '-' . $request->netin_month;
        }else{
            // p('hehe');
            $dt = Carbon::now(); //当前日期

            $netin_year  = $dt->year;  //当前年
            $netin_month = $dt->month; //当前月
            // dd(strlen($netin_month));
            if(strlen($netin_month) == 1){
                $netin_month = '0'.$netin_month;
            }
            $netin  = $netin_year . '-' . $netin_month;
            $request['netin_year']  = '';
            $request['netin_month'] = '';
            // dd($netin_year);
            // dd($request->all());
        }
        // dd($request->all());
        return $request;
    }


    /**
     * Show the form for editing the specified resource.
     * 导入文件
     * @param  
     * @return \Illuminate\Http\Response
     */
    public function exportExcel(Request $request)
    {
        
        
        $dt = Carbon::now(); //当前日期

        $now_year  = $dt->year;  //当前年
        $now_month = $dt->month; //当前月
        // dd(strlen($netin_month));
        if(strlen($now_month) == 1){
            $now_month = '0'.$now_month;
        }

        /*$netin  = !empty($request->netin_year) ? $request->netin_year : $now_year . '-' . !empty($request->netin_month) ? $request->netin_month : $now_month;*/

        if(empty($request->netin_year)){

            $request['netin_year'] = $now_year;
        }

        if(empty($request->netin_month)){

            $request['netin_month'] = $now_month;
        }

        $request['withNoPage'] = true;

       

        $excel_info = $this->infoSelf->getAllInfos($request);

        

        // dd(lastSql());
        // dd($excel_info[0]->side_number);

        $info_content = [];
        foreach ($excel_info as $key => $value){

            $fuka_info = null;

            /*foreach ($value->hasManyOrderGoods as $key => $goods) {
                $goods_info .= $goods->category_name;
                $goods_info .= $goods->goods_name;
                $goods_info .= $goods->goods_num;
                $goods_info .= "\r\n";
            }

            $goods_info .= '发件人:'.$value->send_name;
            
            if(!empty($value->remark)){
                $goods_info .= "\r\n";
                $goods_info .= '备注:';
                $goods_info .= $value->remark;
            }*/

            if(!empty($value->side_number)){

                $side_number_arr     = explode("|",  $value->side_number);
                $side_uim_number_arr = explode("|",  $value->side_uim_number);

                foreach ($side_number_arr as $k => $v) {
                    $fuka_info .= $v;
                    $fuka_info .= '(';
                    $fuka_info .= $side_uim_number_arr[$k];
                    $fuka_info .= ')';
                    $fuka_info .= "\r\n";
                }

                // dd($fuka_info);
                $fuka_info = substr($fuka_info,0,strlen($fuka_info)-2); 
            }
            
            if($value->is_jituan == 1){
                $jituan_info = '是';
            }else{
                $jituan_info = '否';
            }

            if($value->old_bind == 1){
                $old_bind_info = '是';
            }else{
                $old_bind_info = '否';
            }


            $info_content[] =  array(
                $key+1,
                substr($value->created_at, 0 ,10),
                $value->manage_name, 
                $value->manage_telephone,                 
                $value->project_name,
                $value->name,
                $value->new_telephone,
                $value->uim_number,
                $jituan_info,
                $old_bind_info,
                $fuka_info,
                isset($value->hasOnePackage->name) ? $value->hasOnePackage->name : '',
                $value->user_telephone,
                $value->collections,
                config('zhuorui.collections_type')[$value->collections_type],
                $value->belongsToCreater->nick_name,
            );
        }

        
        $titile_arr = ['序号','日期','客户经理','电话','项目', '客户姓名', '新号码', 'UIM码', '集团卡', '绑老卡','副卡(UIM)','套餐标准', '联系方式', '收款', '收款方式', '销售人'];

        array_unshift($info_content, $titile_arr);

        

        $excels = Excel::create('信息',function($excel) use ($info_content){
            $excel->sheet('score', function($sheet) use ($info_content){
                $sheet->setWidth('A', 5);
                $sheet->setWidth('B', 10);
                $sheet->setWidth('C', 10);
                $sheet->setWidth('D', 15);
                $sheet->setWidth('E', 10); 
                $sheet->setWidth('G', 20); 
                $sheet->setWidth('H', 30); 
                $sheet->setWidth('I', 5); 
                $sheet->setWidth('J', 5); 
                $sheet->setWidth('K', 50); 
                $sheet->setWidth('L', 30); 
                $sheet->setWidth('M', 15); 
                $sheet->setWidth('N', 5); 
                $sheet->setWidth('O', 10); 
                $sheet->setWidth('P', 10); 
                $sheet->setFontSize(15);
                // $sheet->setValignment('middle');      
                $sheet->rows($info_content);
            });
        });

        
        // dd($excels->save());
        $excels->export('xlsx');
    }
}
