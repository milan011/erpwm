<?php

namespace App\Providers;

use App\Audittrail;
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
        DB::listen(function ($query) use ($user_id) {

            $sql = str_replace('?', '"' . '%s' . '"', $query->sql);
            $sql = @vsprintf($sql, $query->bindings);
            $sql = str_replace("\\", "", $sql);
            // Log::channel("sql")->info(' execution time: ' . $query->time . 'ms; ' . $tmp . "\t");
            $action = substr($sql, 0, 6);
            //如果是更新删除操作则存储到数据库里
            if ($action == 'update' || $action == 'delete' || $action == 'insert') {
                // dd($sql);
                // dd($query);
                $domain = strstr($sql, 'audittrail');

                if (!$domain) {
                    // $actionModel = new Audittrail();

                    $sqlData['querystring']     = $sql;
                    $sqlData['transactiondate'] = date('Y-m-d H:i:s');
                    $sqlData['userid']          = $user_id;
                    // dd($sqlData);
                    $log_path = 'logs\sql-' . date('Y-m-d') . '.log';
                    $filepath = storage_path($log_path);
                    file_put_contents($filepath, $sqlData['querystring'] . '用户' . $user_id . "\r\n", FILE_APPEND);
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
