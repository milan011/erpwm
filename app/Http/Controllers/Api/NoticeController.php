<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
// use App\Http\Requests\Notice\UpdateNoticeRequest;
// use App\Http\Requests\Notice\StoreNoticeRequest;
// use App\Repositories\Notice\NoticeRepositoryContract;


use App\Repositories\Notice\NoticeRepositoryInterface;
use App\Http\Resources\Notice\NoticeResource;
use App\Http\Resources\Notice\NoticeResourceCollection;

class NoticeController extends Controller
{
    protected $notice;

    public function __construct(
        NoticeRepositoryInterface $notice
    ) {
    
        $this->notice = $notice;
        // $this->middleware('notice.create', ['only' => ['create']]);
    }

    /**
     * Display a listing of the resource.
     * 公告列表
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notices = $this->notice->allNotices();

        return new NoticeResource($notices);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.notice.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNoticeRequest $noticeRequest)
    {
        $getInsertedId = $this->notice->create($noticeRequest);
        // p(lastSql());exit;
        return redirect()->route('admin.notice.index')->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notice_info = $this->notice->find($id);
        // dd(lastSql());
        // dd($notice_info);exit;
        return view('admin.notice.show', compact(

            'notice_info'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     * 编辑公告
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $notice_info = $this->notice->find($id);
        // dd(lastSql());
        // p($notice_info);exit;
        return view('admin.notice.edit', compact(

            'notice_info'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNoticeRequest $noticeRequest, $id)
    {
        // dd($noticeRequest->all());
        $this->notice->update($noticeRequest, $id);
        return redirect()->route('admin.notice.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($id);
        $this->notice->destroy($id);        
        return redirect()->route('admin.notice.index');
    }
}
