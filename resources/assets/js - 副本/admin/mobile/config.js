/**
 * Defines the API route we are using.
 */
var api_url = 'http://www.myvue.net/api';
var app_url = 'http://www.myvue.net';
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

//信息状态
const infoSelfStatus = {
    1: '未返还',
    2: '返还中',
    3: '已返还',
};
//电信信息状态
const infoDianxinStatus = {
    1: '未匹对',
    2: '已匹对',
};
//集团状态
const jituanStatus = {
    0: '否',
    1: '是',
};

//绑老卡状态
const oldBindStatus = {
    0: '否',
    1: '是',
};

//是否食品
const foodStatus = {
    0: '否',
    1: '是',
};


//serviceReturnType
const serviceReturnType = {
    1: '按比例',
    2: '按金额',
};

//入网年
const package_year = [
    {'key': '1', 'year': '2018'},
    {'key': '2', 'year': '2019'},
    {'key': '3', 'year': '2020'},
    {'key': '4', 'year': '2021'},
    {'key': '5', 'year': '2022'},
    {'key': '6', 'year': '2023'},
    {'key': '7', 'year': '2024'},
    {'key': '8', 'year': '2025'},
    {'key': '9', 'year': '2026'},
]

//入网月
const package_month = [
    {'key': '1', 'month': '01'},
    {'key': '2', 'month': '02'},
    {'key': '3', 'month': '03'},
    {'key': '4', 'month': '04'},
    {'key': '5', 'month': '05'},
    {'key': '6', 'month': '06'},
    {'key': '7', 'month': '07'},
    {'key': '8', 'month': '08'},
    {'key': '9', 'month': '09'},
    {'key': '10', 'month': '10'},
    {'key': '11', 'month': '11'},
    {'key': '12', 'month': '12'},
]
//支付方式
const collections_type = ['微信', '支付宝', '刷卡', '现金', '其他']

export { 
    ROAST_CONFIG, 
    infoSelfStatus,
    jituanStatus, 
    package_year, 
    package_month,
    collections_type,
    oldBindStatus,
    infoDianxinStatus,
    foodStatus,
    serviceReturnType,
}

/*export const zrConfig = {
    infoSelfStatus: infoSelfStatus,
    jituanStatus: jituanStatus,
};*/
