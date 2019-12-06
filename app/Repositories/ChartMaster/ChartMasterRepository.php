<?php
namespace App\Repositories\ChartMaster;

use App\ChartDetail;
use App\ChartMaster;
use App\Periods;
use App\Repositories\BaseInterface\Repository;
use App\Repositories\ChartMaster\ChartMasterRepositoryInterface;
use Auth;
use Carbon;
use Datatables;
use DB;
use Debugbar;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PHPZen\LaravelRbac\Traits\Rbac;
use Planbon;
use Session;

class ChartMasterRepository implements ChartMasterRepositoryInterface
{
    //默认查询数据
    protected $select_columns = ['id', 'accountcode', 'accountname', 'group_', 'status'];

    // 根据ID获得信息
    public function find($id)
    {
        return ChartMaster::select($this->select_columns)
            ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getList($queryList)
    {
        $query = new ChartMaster(); // 返回的是一个Order实例,两种方法均可
        $query = $query->with('belongsToAccountGroup', 'belongsToBankAccount')->where('status', '1');
        if (empty($queryList['page'])) {
            //无分页,全部返还
            return $query->get();
        } else {
            $query->orderBy('id', 'DESC');
            return $query->paginate(10);
        }
    }

    // 创建信息
    public function create($requestData)
    {

        DB::beginTransaction();
        try {

            $newChart = new ChartMaster(); //会计科目

            $input = array_replace($requestData->all());
            $input = nullDel($input);
            $newChart->fill($input);

            $newChart = $newChart->create($input);

            DB::commit();
            return $newChart;

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
        $info = ChartMaster::select($this->select_columns)->findorFail($id); //获取信息

        $info->accountname = $requestData->accountname;
        $info->group_      = $requestData->group_;

        $info->save();

        return $info;
    }

    // 删除信息
    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $info = ChartMaster::findorFail($id);

            $returnData['status']  = true;
            $returnData['message'] = '';

            if ($info->hasManyChartDetail->count() > 0) {
                $returnData['status']  = false;
                $returnData['message'] = '删除会计科目失败，原因是：科目明细账已存在';

                return $returnData;
            }

            if ($info->hasManyGltrans->count() > 0) {
                $returnData['status']  = false;
                $returnData['message'] = '删除会计科目失败，已存在交易';

                return $returnData;
            }

            $comUseNum = DB::select("SELECT COUNT(*) AS count FROM companies
                    WHERE debtorsact='" . $id . "'
                    OR pytdiscountact='" . $id . "'
                    OR creditorsact='" . $id . "'
                    OR payrollact='" . $id . "'
                    OR grnact='" . $id . "'
                    OR exchangediffact='" . $id . "'
                    OR purchasesexchangediffact='" . $id . "'
                    OR retainedearnings='" . $id . "'");

            if ($comUseNum[0]->count > 0) {
                $returnData['status']  = false;
                $returnData['message'] = '删除会计科目失败，原因是：已作为公司预设会计科目';

                return $returnData;
            }

            $taxUseNum = DB::select("SELECT COUNT(*) AS count  FROM taxauthorities
                    WHERE taxglcode='" . $id . "'
                    OR purchtaxglaccount ='" . $id . "'");

            if ($taxUseNum[0]->count > 0) {
                $returnData['status']  = false;
                $returnData['message'] = '删除会计科目失败, 原因是：已作为税务科目';

                return $returnData;
            }

            $salUseNum = DB::select("SELECT COUNT(*) AS count FROM salesglpostings
                        WHERE salesglcode='" . $id . "'
                        OR discountglcode='" . $id . "'");

            if ($salUseNum[0]->count > 0) {
                $returnData['status']  = false;
                $returnData['message'] = '删除会计科目失败，原因是：已作为销售收入科目';

                return $returnData;
            }

            $salcUseNum = DB::select("SELECT COUNT(*) AS count
                                FROM cogsglpostings
                                WHERE glcode='" . $id . "'");

            if ($salcUseNum[0]->count > 0) {
                $returnData['status']  = false;
                $returnData['message'] = '删除会计科目失败，原因是：已作为销售成本科目';

                return $returnData;
            }

            $stockUseNum = DB::select("SELECT COUNT(*) AS count
                                FROM stockcategory
                                    WHERE stockact='" . $id . "'
                                    OR adjglact='" . $id . "'
                                    OR purchpricevaract='" . $id . "'
                                    OR materialuseagevarac='" . $id . "'
                                    OR wipact='" . $id . "'");

            if ($stockUseNum[0]->count > 0) {
                $returnData['status']  = false;
                $returnData['message'] = '删除会计科目失败，原因是：已作为存货科目';

                return $returnData;
            }

            $bankUseNum = DB::select("SELECT COUNT(*) AS count
                                FROM bankaccounts
                                WHERE accountcode='" . $id . "'");

            if ($bankUseNum[0]->count > 0) {
                $returnData['status']  = false;
                $returnData['message'] = '删除会计科目失败, 原因是：已作为银行科目';

                return $returnData;
            }

            $info->status = '0'; //删除会计科目
            $info->save();

            DB::commit();
            return $returnData;

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return false;
        }
    }

    //名称是否重复
    public function isRepeat($accountname)
    {
        return ChartMaster::where('accountname', $accountname)->where('status', '1')->first();
    }

    // 获取总帐预算信息
    public function getGLBInfo($id)
    {
        // dd($id);
        /*
         **1.根据当前日期获取上财年,本财年,下财年perid列表
         **2.根据perid列表获取总帐预算信息
         */
        $time = Carbon::now();

        $currentYear    = $time->year; // 本财政年
        $currentYearEnd = $currentYear . '-12-31'; // 本财政年末日期
        $perYear        = $currentYear - 1; //上财政年
        $perYearEnd     = $perYear . '-12-31'; //上财政年末日期
        $nextYear       = $currentYear + 1; //下财政年
        $nextYearEnd    = $nextYear . '-12-31'; //下财政年末日期

        /*p($currentYear);
        p($perYear);
        dd($nextYear);*/

        $peridCurrentList = []; //当前财政年perid列表
        $peridPerList     = []; //上财政年perid列表
        $peridNextList    = []; //下财政年perid列表

        $peridCurrent = Periods::where('lastdate_in_period', 'like', $currentYear . '%')->get();
        $peridPer     = Periods::where('lastdate_in_period', 'like', $perYear . '%')->get();
        $peridNext    = Periods::where('lastdate_in_period', 'like', $nextYear . '%')->get();

        // dd($peridCurrent->all());

        foreach ($peridCurrent as $key => $value) {
            # code...
            $peridCurrentList[] = $value->periodno;
        }

        foreach ($peridPer as $key => $value) {
            # code...
            $peridPerList[] = $value->periodno;
        }

        foreach ($peridNext as $key => $value) {
            # code...
            $peridNextList[] = $value->periodno;
        }

        //获取上财政年,本财政年,下财政年总帐
        $peridCurrentDetail = ChartDetail::with('belongsToPeriods')->whereIn('period', $peridCurrentList)
            ->where('accountid', $id)
            ->get();

        $peridPerDetail = ChartDetail::with('belongsToPeriods')->whereIn('period', $peridPerList)
            ->where('accountid', $id)
            ->get();

        $peridNextDetail = ChartDetail::with('belongsToPeriods')->whereIn('period', $peridNextList)
            ->where('accountid', $id)
            ->get();

        // dd(lastSql());
        // dd($peridCurrentDetail->all());
        /*p($peridPerList);
        p($peridCurrentList);
        dd($peridNextList);*/
        $info = [
            'pre'     => $peridPerDetail,
            'current' => $peridCurrentDetail,
            'next'    => $peridNextDetail,
        ];
        return $info;
    }
}
