<?php

namespace App\Providers;

use App\Audittrail;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        //
        Schema::defaultStringLength(191);
        // dd($request->header('uid'));
        //将增,删,改sql保存
        $user_id = $request->header('uid');
        $user    = User::find($user_id);
        // dd($user->realname);
        $user_name = !empty($user->realname) ? $user->realname : '';
        // dd($user_name);
        // dd(User::find($user_id)->realname);
        DB::listen(function ($query) use ($user_name, $user_id) {

            $sql = str_replace('?', '"' . '%s' . '"', $query->sql);
            $sql = @vsprintf($sql, $query->bindings);
            $sql = str_replace("\\", "", $sql);
            // Log::channel("sql")->info(' execution time: ' . $query->time . 'ms; ' . $tmp . "\t");
            $action = substr($sql, 0, 6);

            //如果是更新删除操作则存储到数据库里
            if ($action == 'update' || $action == 'delete' || $action == 'insert') {
                $auditrail_table = strstr($sql, 'audittrail');

                if (!$auditrail_table) {
                    $actionModel = new Audittrail();

                    $sqlData['querystring']     = $sql;
                    $sqlData['transactiondate'] = date('Y-m-d H:i:s');
                    $sqlData['userid']          = $user_id;
                    $sqlData['username']        = $user_name;

                    // dd(collect($sqlData)->toJson());
                    if ($action != 'insert') {
                        $actionModel->create($sqlData);
                    } else {
                        $logData  = collect($sqlData)->toJson();
                        $log_path = 'logs\sql-' . date('Y-m-d') . '.log';
                        $filepath = storage_path($log_path);
                        file_put_contents($filepath, $logData . "\r\n", FILE_APPEND);
                    }
                }

            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
