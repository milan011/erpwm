<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
// use App\Http\Resources\MRPCalendar\MRPCalendarResource;
// use App\Http\Resources\MRPCalendar\MRPCalendarResourceCollection;
use App\Repositories\MRPCalendar\MRPCalendarRepositoryInterface;
use Carbon;
use Illuminate\Http\Request;

class MRPCalendarController extends Controller
{
    protected $mRPCalendar;

    public function __construct(

        MRPCalendarRepositoryInterface $mRPCalendar
    ) {
        $this->mRPCalendar = $mRPCalendar;
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query_list = jsonToArray($request); //获取搜索信息

        $mRPCalendars = $this->mRPCalendar->getList($query_list);

        return $mRPCalendars;
    }

    /**
     * Show the form for creating a new resource.
     * 创建(基于接口的开发方式不需要该方法)
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * 存储
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->startDate > $request->endDate) {
            // dd('hehe');
            $middle               = $request->startDate;
            $request['startDate'] = $request->endDate;
            $request['endDate']   = $middle;
        }

        /*dd($request->startDate - $request->endDate);
        dd($request->all());*/
        $startDate = new Carbon($request->startDate);
        $endDate   = new Carbon($request->endDate);
        // $from = Carbon::createFromDate(2017, 7, 21);
        // p($from);
        // dd($startDate);

        $dates = []; //起始日期间所有日期
        $list  = []; //日期,星期对应数组
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
            // $dates[]['week'] = $date->dayOfWeek;
            // p($date);
        }
        /*dd($dates);
        p($dates[0]);
        dd($dates[1]);
        $dt = $endDate->dayOfWeek;*/
        foreach ($dates as $key => $value) {
            // p(new Carbon($value));
            $d                  = new Carbon($value);
            $list[$key]['day']  = $d->format('Y-m-d');
            $list[$key]['week'] = $d->dayOfWeek;
        }
        // dd($list);
        // dd($request->checkList);

        $mrpData = [];

        $daynumber = 0;
        foreach ($list as $key => $value) {
            # code...
            $mrpData[$key]['calendardate'] = $value['day'];
            if (!in_array($value['week'], $request->checkList)) {
                //是否工作日
                $daynumber++;
                $mrpData[$key]['daynumber']         = $daynumber;
                $mrpData[$key]['manufacturingflag'] = 1;
            } else {
                $mrpData[$key]['daynumber']         = $daynumber;
                $mrpData[$key]['manufacturingflag'] = 0;
            }
        }
        // dd($mrpData);

        $info = $this->mRPCalendar->create($mrpData);

        if ($info) {
            //添加成功
            return $this->baseSucceed($respond_data = $info, $message = '添加成功');
        } else {
            //添加失败
            return $this->baseFailed($message = '内部错误');
        }
    }

    /**
     * Display the specified resource.
     * 根据id获取特定实例
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $info = $this->mRPCalendar->find($id);
        $info->belongsToCreater;

        return new MRPCalendarResource($info);
    }

    /**
     * Show the form for editing the specified resource.
     * 编辑(基于接口的开发方式不需要该方法)
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
        $update_info = $this->mRPCalendar->isRepeat($request->name);

        if ($update_info && ($update_info->id != $id)) {
            return $this->baseFailed($message = '您修改后的信息与现有冲突');
        }

        $info = $this->mRPCalendar->update($request, $id);
        $info->hasOnePackage;

        return $this->baseSucceed($respond_data = $info, $message = '修改成功');
    }

    /**
     * Remove the specified resource from storage.
     * 删除
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($id);
        $this->mRPCalendar->destroy($id);
        return $this->baseSucceed($message = '修改成功');
    }
}
