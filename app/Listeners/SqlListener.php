<?php

namespace App\Listeners;

use Illuminate\Database\Events\QueryExecuted;

class SqlListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    /**
     * Handle the event.
     *
     * @param =QueryExecuted $event
     * @return void
     */
    public function handle(QueryExecuted $event)
    {
        // 在这里编写业务逻辑
        $sql      = str_replace("?", "'%s'", $event->sql);
        $log      = vsprintf($sql, $event->bindings);
        $log      = '[' . date('Y-m-d H:i:s') . '] ' . $log . "\r\n";
        $filepath = storage_path('logs\sql.log');

        \Log::useDailyFiles($filepath, days:5, level:'notice');
        \Log::notice($log);
    }
}
