<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Auth;
use Gate;
use DB;
use Excel;
use Session;
use Carbon;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Resources\InfoDianxin\InfoDianxinResource;
use App\Http\Resources\InfoDianxin\InfoDianxinResourceCollection;
use App\Repositories\InfoDianxin\InfoDianxinRepositoryInterface;
use App\Repositories\InfoSelf\InfoSelfRepositoryInterface;


class InfoDianxinController extends Controller
{   
    protected $infoDianxin;
    protected $infoSelf;
    

    public function __construct(

        InfoDianxinRepositoryInterface $infoDianxin,
        InfoSelfRepositoryInterface $infoSelf
        // ShopRepositoryContract $shop
    ) {
    
        $this->infoDianxin = $infoDianxin;
        $this->infoSelf    = $infoSelf;
        /*$this->brands = $brands;
        $this->shop = $shop;*/


        // $this->middleware('brand.create', ['only' => ['create']]);
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $request['status'] = '1';
        $query_list = jsonToArray($request->input('query')); //获取搜索信息

        // isset($query_list['withNoPage']) ? : false;

        //$query_list['withNoPage'] = ($query_list['withNoPage']) ? true : false ;

        $query_list['netin'] = $this->netinQuery($query_list['netin_year'], $query_list['netin_month']);

        // dd($query_list);
        
        $infos = $this->infoDianxin->getAllDianXinInfos($query_list);
        
        return new InfoDianxinResource($infos);
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

        // $managers = $this->manager->getManagers(); // 电信客户经理
        // $packages = $this->package->getPackages(); // 电信客户经理

        return view('admin.infoDianxin.create', compact('dt_year', 'dt_month'));
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

        if($this->infoDianxin->isRepeat($request->return_telephone, $request->balance_month)){
            return $this->baseFailed($message = '入网号码及返回月已存在');
        }

        $info = $this->infoDianxin->create($request);
        // dd($info);
        /*$creater = $info->belongsToCreater;
        p(lastSql());
        dd($creater);*/
        // $info->belongsToInfoSelf;
        // $info->belongsToCreater = $info->belongsToCreater;
        $info->belongsToCreater;
        // dd($info);

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

        $info         = $this->infoDianxin->find($id);
        // $package_info = $info->hasOnePackage;

         // dd($info);

        return view('admin.infoDianxin.show', compact('info'));
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
        $info              = $this->infoDianxin->find($id);
        
        // dd($info);

        $netin_date  = explode('-', $info->netin); //入网日期转数组
        $netin_year  = $netin_date[0]; //入网年
        $netin_month = $netin_date[1]; //入网月


        return view('admin.infoDianxin.edit', compact(
            'info','netin_year', 'netin_month'
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

        $update_info = $this->infoDianxin->isRepeat($request->return_telephone, $request->balance_month);
        if($update_info && ($update_info->id != $id)){
            return $this->baseFailed($message = '您修改后的信息与现有信息冲突');
        }

        $info = $this->infoDianxin->update($request, $id);
        
        return $this->baseSucceed($respond_data = $info, $message = '修改成功');
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
        $queryList['unPayed']      = '1';
        $queryList['withNoPage']   = true; //获取全部数据
        // $notPayed = true;
        // dd($select_conditions);
        // $be = time();
        $infoDealNum = $this->infoSelf->infoDeal($queryList); //尚未返还完成信息
        // $af = time();

        // dd($af - $be);

        // dd($infoDealNum);


        //处理已经返还完成信息
        /*if($infoSelfs_not_payed != 0){
           $infoSelfs_payed = $this->infoSelf->infopayed(); 
        }*/
         
        // dd('呵呵');
        return $this->baseSucceed($respond_data = $infoDealNum, $message = '处理完成,本次共处理'.$infoDealNum.'条信息');

        /*return response()->json(array(
            'status'      => 1,
            'msg'         => '处理完成',
            'dealInfoNum' => $infoSelfs_not_payed,
        ));*/
        
        // return redirect('infoSelf/notPayed')->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($request->all());

        // dd($id);
        $this->infoDianxin->destroy($id);

        return $this->baseSucceed($message = '修改成功');
    }

    // ajax修改商品价格
    public function ajaxUpdatePrice(Request $request){
        // p($request->all());exit;

        $price = $this->goodsPrice->updateAjax($request); 
        // p($price);exit;
        return response()->json(array(
            'status'      => 1,
            'msg'         => '修改成功',
        ));
    }

    /**
     * Show the form for editing the specified resource.
     * 错误页面
     * @param  
     * @return \Illuminate\Http\Response
     */
    public function error()
    {
        return view('admin.errors.icon');
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

            $netin = $netin_year . $netin_month;
        }

        return $netin;
    }
    /**
     * Show the form for editing the specified resource.
     * 导入文件
     * @param  
     * @return \Illuminate\Http\Response
     */
    public function importInfo(Request $request)
    {
        
        /*dd($request->post());
        $collection = collect([1, 2, 3]);*/

        $infoData = collect($request->post())->unique(); //获取数据并清除重复
        // $infoData = $infoData->unique(); //清除重复数据

        // dd($infoData);

        $time_start = time();//得到当前时间戳，用来在最后计算文件导入完毕后的用时
        
        $info_count_before = DB::table('zr_info_dianxin')->count(); //插入前总数据量

        $data          = []; //导入数据
        $dataNotImport = []; //未导入数据

        foreach ($infoData as $key => $value) { //遍历数据
            $row["name"]             = trim($value['套餐名称']);//套餐名称
            $row["return_telephone"] = (string)trim($value['返款号码']);//返款号码
            $row["refunds"]          = trim($value['返款金额']);//返款金额
            $row["yongjin"]          = trim($value['佣金方案']);//佣金方案
            $row["balance_month"]    = (string)trim($value['结算月']);//结算月
            $row["netin"]            = (string)trim($value['返还日期']);//返还日期
            $row["jiakuan"]          = trim($value['价款']);//价款
            $row["manager"]          = trim($value['客户经理']);//客户经理
            $row["jituan"]           = trim($value['集团名称']);//
            array_push($data, $row);
        }
        // dd($data);
        $success_count = 0;
        $un_success_count = 0;

        foreach($data as $k=>$d){
            if(!$d)continue;
            try {
                $infoDianxin = $this->infoDianxin->createImport($d);
                $success_count++;
            } catch (\Exception $e) {
                /*p($e->getMessage());
                p($k);
                dd($d);*/
                array_push($dataNotImport, $d);
                $un_success_count++;
            }  
            
            /*DB::transaction(function ()use($d) {//一些导入操作
                $insert_id = DB::table("zr_info_dianx")->insertGetId($d);
                //一些数据库操作
            });*/
            /*p('hehe');
            dd(lastSql());*/   
        }
        
        /*p($success_count);
        p($un_success_count);
        dd($dataNotImport);*/

        /*try{
            //导入执行代码              
            
        }catch(Exception $e){
                //自定义处理异常
        }*/ 
        $info_count_after = DB::table('zr_info_dianxin')->count(); //插入前总数据量
        $info_nums = $info_count_after - $info_count_before;
        $time_end = time();//得到当前时间戳，用来在最后计算文件导入完毕后的用时
        $time_use = $time_end - $time_start;

        $message = "本次共导入 ".$info_nums.' 条数据.'.'若导入数据数目跟您表格中数目不相符,说明您表格中某些数据系统中已经存在';
        
        return response()->json(array(
            'status' => 1,
            'success_count'    => $success_count,
            'un_success_count' => $un_success_count,
            'dataNotImport'    => $dataNotImport,
            /*'time_start'         => $time_start,
            'time_end'         => $time_end,
            'time_use'         => $time_use,*/
        ));
    }

    //下载标准表格
    public function exampleExcelDownload()
    {
        return response()->download(realpath(public_path('uploads/example.xlsx')));
    }

    //下载谷歌浏览器
    public function chormeBroswerDown()
    {
        return response()->download(realpath(public_path('uploads/ChromeSetup_32.exe')));
    }
}
