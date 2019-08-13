<?php
namespace App\Repositories\SysType;

use App\Repositories\BaseInterface\Repository;
use App\Repositories\SysType\SysTypeRepositoryInterface;
use App\SysType;
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

class SysTypeRepository implements SysTypeRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['typeid', 'typename', 'typeno'];

    // 根据ID获得信息
    public function find($id)
    {
        return SysType::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new SysType(); // 返回的是一个Order实例,两种方法均可
        $query = $query->where('status', '1')->orderBy('id', 'DESC');
        // $query = $query->with('');
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

            $example = new SysType(); //税目

            $input = array_replace($requestData->all());
            $example->fill($input);
            $example = $example->create($input);

            DB::commit();
            return $example;

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
        $info = SysType::select($this->select_columns)->findorFail($id); //获取信息

        $info->taxcatname = $requestData->taxcatname;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info         = SysType::findorFail($id);
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
    public function isRepeat($taxcatname)
    {
        return SysType::where('taxcatname', $taxcatname)->where('status', '1')->first();
    }

    /*处理记录的代码信息*/
    public function delTypeno($id)
    {
        $info         = SysType::findorFail($id);
        $info->typeno = $info->typeno + 1; //删除税目
        $info->save();
        // dd($info);
        DB::commit();
        return $info->typeno;
    }
}
