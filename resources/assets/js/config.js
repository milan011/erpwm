/**
 * Defines the API route we are using.
 */
var api_url = 'http://www.mycnc.net/api';
var app_url = 'http://www.mycnc.net';
/*var api_url = 'http://www.erpwm.com/api';
var app_url = 'http://www.erpwm.com';*/
/*var gaode_maps_js_api_key = '33c20882595f1fecc2d31c8c73a38da7';

switch( process.env.NODE_ENV ){
    case 'development':
        api_url = 'http://roast.test/api/v1';
        app_url = 'http://roast.test';
        break;
    case 'production':
        api_url = 'http://roast.demo.laravelacademy.org/api/v1';
        app_url = 'http://roast.demo.laravelacademy.org';
        break;
}*/
const ROAST_CONFIG = {
    API_URL: api_url,
    APP_URL: app_url,
    // GAODE_MAPS_JS_API_KEY: gaode_maps_js_api_key
};

//存货类型
const stockType = {
  F: '产成品', 
  M: '原材料',
  D: '虚拟物料(无库存)',
  L: '人工',
};

//折旧类型
const depnType = {
  1: '直线', 
  2: '减值折旧法',
};

//星期
const weekDay = [
  {'key': '1', 'day': '星期一'},
  {'key': '2', 'day': '星期二'},
  {'key': '3', 'day': '星期三'},
  {'key': '4', 'day': '星期四'},
  {'key': '5', 'day': '星期五'},
  {'key': '6', 'day': '星期六'},
  {'key': '0', 'day': '星期天'},
]

export { 
    ROAST_CONFIG, 
    stockType,
    weekDay,
    depnType,
}

/*export const zrConfig = {
    infoSelfStatus: infoSelfStatus,
    jituanStatus: jituanStatus,
};*/
