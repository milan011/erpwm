<?php

//打印测试数据函数
function p($s)
{

    echo '<pre>';
    var_dump($s);
    echo '</pre>';
}

// 获取最后一条sql
function lastSql()
{
	\DB::connection()->enableQueryLog(); // 开启查询日志

    $sql = DB::getQueryLog();

    $query = end($sql);

    return $query;
}

//二维数组去掉重复值
function a_array_unique($array){
    $out = array();

    foreach ($array as $key=>$value) {
        if (!in_array($value, $out)){
            $out[$key] = $value;
        }
    }

    $out = array_values($out);
    return $out;
}

/**
 * 获得信息编号
 */
function getInfoCode($type = 'info')
{
    // 信息编号为Unix时间戳后9位
    $code = 'I-';   
    $date = (string)(time());
    $date =  substr ($date, 1); 
    $code .= $date;
    return $code;
}

/**
 * Json对象转数组
 */
function jsonToArray($json = '')
{
    return json_decode($json, true);
}