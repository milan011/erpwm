<?php
namespace App\Repositories\Tags;

use App\Repositories\BaseInterface\Repository;
use App\Repositories\Tags\TagsRepositoryInterface;
use App\Tags;
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

class TagsRepository implements TagsRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['tagref', 'tagdescription', 'status'];

    // 根据ID获得信息
    public function find($id)
    {
        return Tags::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new Tags(); // 返回的是一个Order实例,两种方法均可
        $query = $query->where('status', '1')->orderBy('tagref', 'DESC');
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

            $example = new Tags(); //标签

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
        $info = Tags::select($this->select_columns)->findorFail($id); //获取信息

        $info->tagdescription = $requestData->tagdescription;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $info = Tags::findorFail($id);

            $returnData['status']  = true;
            $returnData['message'] = '';

            if ($info->hasManyGltrans->count() > 0) {
                $returnData['status']  = false;
                $returnData['message'] = '删除标签失败，原因是：该标签已经被使用';
                // dd(lastSql());
                return $returnData;
            }

            $info->status = '0'; //删除标签
            $info->save();

            DB::commit();
            return $returnData;

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return false;
        }
    }

    //名称是否重复
    public function isRepeat($tagdescription)
    {
        return Tags::where('tagdescription', $tagdescription)->where('status', '1')->first();
    }
}
