<?php
namespace App\Repositories\AccountSection;

use App\AccountSection;
use App\Repositories\AccountSection\AccountSectionRepositoryInterface;
use App\Repositories\BaseInterface\Repository;
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

class AccountSectionRepository implements AccountSectionRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['sectionid', 'sectionname', 'status'];

    // 根据ID获得信息
    public function find($id)
    {
        return AccountSection::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new AccountSection(); // 返回的是一个Order实例,两种方法均可
        // $query = $query->addCondition($queryList); //根据条件组合语句
        $query = $query->where('status', '1')->orderBy('sectionid', 'DESC');
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

            $example = new AccountSection(); //税目

            $input = array_replace($requestData->all());
            $input = nullDel($input);
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
        $info = AccountSection::select($this->select_columns)->findorFail($id); //获取信息

        $info->sectionname = $requestData->sectionname;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info = AccountSection::findorFail($id);

            $returnData['status']  = true;
            $returnData['message'] = '';
            // dd($info->hasManyAccountGroup->count());
            if ($info->hasManyAccountGroup->count() > 0) {
                $returnData['status']  = false;
                $returnData['message'] = '该要素已被科目组使用,无法删除';

                return $returnData;
            }

            $info->status = '0'; //删除会计要素
            $info->save();

            DB::commit();
            return $info;

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return false;
        }
    }

    //名称是否重复
    public function isRepeat($sectionname)
    {
        return AccountSection::where('sectionname', $sectionname)->where('status', '1')->first();
    }
}
