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

export { 
    ROAST_CONFIG, 
    stockType,
}

/*export const zrConfig = {
    infoSelfStatus: infoSelfStatus,
    jituanStatus: jituanStatus,
};*/
