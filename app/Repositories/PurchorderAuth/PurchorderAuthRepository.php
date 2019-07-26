<?php
namespace App\Repositories\PurchorderAuth;

use App\PurchorderAuth;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\PurchorderAuth\PurchorderAuthRepositoryInterface;
use Auth;
use Datatables;
use DB;
use Debugbar;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PHPZen\LaravelRbac\Traits\Rbac;
use Planbon;
use Session;

class PurchorderAuthRepository implements PurchorderAuthRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id', 'userid', 'uid', 'currabrev', 'cancreate', 'authlevel', 'offhold'];

    // 根据ID获得信息
    public function find($id)
    {
        return PurchorderAuth::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new PurchorderAuth(); // 返回的是一个Order实例,两种方法均可
        $query = $query->with('belongsToUser', 'belongsToCurrencies')->where('status', '1');
        $query = $query->orderBy('id', "DESC");
        if (empty($queryList['page'])) {
            //无分页,全部返还
            return $query->get();
        } else {
            return $query->paginate(10);
        }
    }

    // 创建信息
    public function create($requestData)
    {

        DB::beginTransaction();
        try {

            $purchorder_auth = new PurchorderAuth(); //税目

            $input = array_replace($requestData->all());
            $purchorder_auth->fill($input);
            $purchorder_auth = $purchorder_auth->create($input);
            // dd($purchorder_auth);

            DB::commit();
            return $purchorder_auth;

        } catch (\Exception $e) {
            throw $e;
            DB::rollBack();
            return false;
        }

    }

    // 信息更新
    public function update($requestData, $id)
    {
        // dd($requestData->all());
        $info = PurchorderAuth::select($this->select_columns)->findorFail($id); //获取信息

        $info->userid    = $requestData->userid;
        $info->cancreate = $requestData->cancreate;
        $info->offhold   = $requestData->offhold;
        $info->currabrev = $requestData->currabrev;
        $info->authlevel = $requestData->authlevel;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info         = PurchorderAuth::findorFail($id);
            $info->status = '0'; //删除税目
            $info->save();

            DB::commit();
            return $info;

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return false;
        }
    }

    //名称是否重复
    public function isRepeat($userid, $currabrev)
    {
        return PurchorderAuth::where('userid', $userid)
            ->where('currabrev', $currabrev)
            ->where('status', '1')
            ->first();
    }
}
