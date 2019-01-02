<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('jwt.auth')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group([
    'middleware' => ['jwt.auth'],
    'namespace' => 'Api',
], function () {
    // User
    Route::get('getUserInfo', 'UserController@getUser');
    Route::get('userAll', 'UserController@userAll');
    Route::post('userLogout', 'UserController@logout');
    Route::get('userList', 'UserController@index');
    Route::post('user', 'UserController@store');
    Route::put('user/{id}', 'UserController@update');
    Route::get('user/{user}/roles', 'UserController@getUserRoles');
    Route::post('giveUser/{user}/roles', 'UserController@giveUserRoles');
    Route::delete('user/{id}', 'UserController@destroy');
    Route::post('resetPassword', 'UserController@resetPassword');

    //Permission
    Route::get('permissionList', 'PermissionController@index');
    Route::get('permissionAll', 'PermissionController@permissionAll'); //所有权限列表,无分页
    Route::post('permission', 'PermissionController@store');
    Route::put('permission/{id}', 'PermissionController@update');
    Route::delete('permission/{id}', 'PermissionController@destroy');

    //Role
    Route::get('roleList', 'RoleController@index');
    Route::post('role', 'RoleController@store');
    Route::put('role/{id}', 'RoleController@update');
    Route::delete('role/{id}', 'RoleController@destroy');
    Route::get('role/{role}/permissions', 'RoleController@getRolePermissions');
    Route::post('giveRole/{role}/permissions', 'RoleController@giveRolePermissions');

    //Manager
    Route::get('managerList', 'ManagerController@index');
    Route::get('managerAll', 'ManagerController@managerAll'); //所有权限列表,无分页
    Route::post('manager', 'ManagerController@store');
    Route::put('manager/{id}', 'ManagerController@update');
    Route::delete('manager/{id}', 'ManagerController@destroy');

    //Package
    Route::get('packageList', 'PackageController@index');
    Route::get('packageAll', 'PackageController@packageAll'); //所有套餐列表,无分页
    Route::post('package', 'PackageController@store');
    Route::get('getPackage/{id}', 'PackageController@getPackage');
    Route::put('package/{id}', 'PackageController@update');
    Route::delete('package/{id}', 'PackageController@destroy');

    //InfoSelf
    Route::get('infoSelfList', 'InfoSelfController@index');
    Route::post('infoSelf', 'InfoSelfController@store');
    Route::get('getInfo/{id}', 'InfoSelfController@getInfo');
    Route::put('infoSelf/{id}', 'InfoSelfController@update');
    Route::delete('infoSelf/{id}', 'InfoSelfController@destroy');
    Route::match(['get', 'post'], 'infoStatistics', 'InfoSelfController@statistics');

    //InfoDianxin
    Route::get('infoDianxinList', 'InfoDianxinController@index');
    // Route::get('infoDianxinAll', 'InfoDianxinController@infoDianxinAll'); //所有信息列表,无分页
    Route::post('infoDianxin', 'InfoDianxinController@store');
    Route::post('importInfoDianxin', 'InfoDianxinController@importInfo');
    Route::get('infoDianxin/exampleExcelDownload', 'InfoDianxinController@exampleExcelDownload');
    // Route::post('infoDianxin/dealWith', 'InfoDianxinController@dealWith')->middleware('throttle:20');
    Route::get('infoDianxin/dealWith', 'InfoDianxinController@dealWith');
    Route::put('infoDianxin/{id}', 'InfoDianxinController@update');
    Route::delete('infoDianxin/{id}', 'InfoDianxinController@destroy');

    Route::get('infoDianxin/chormeBroswerDown', 'InfoDianxinController@chormeBroswerDown');
    
    //Goods
    Route::get('goodsList', 'GoodsController@index');
    Route::get('goodsAll', 'GoodsController@goodsAll'); //所有礼品列表,无分页
    Route::post('goods', 'GoodsController@store');
    Route::get('getGoodsInfo/{id}', 'GoodsController@getInfo');
    Route::put('goods/{id}', 'GoodsController@update');
    Route::delete('goods/{id}', 'GoodsController@destroy');

    //Service
    Route::get('serviceList', 'ServiceController@index');
    Route::get('serviceAll', 'ServiceController@serviceAll'); //所有礼品列表,无分页
    Route::post('service', 'ServiceController@store');
    Route::get('getServiceInfo/{id}', 'ServiceController@getInfo');
    Route::put('service/{id}', 'ServiceController@update');
    Route::delete('service/{id}', 'ServiceController@destroy');

    //ServiceDetail
    Route::get('serviceDetailList', 'ServiceDetailController@index');
    Route::post('serviceDetail', 'ServiceDetailController@store');
    Route::get('getServiceDetail/{id}', 'ServiceDetailController@getInfo');
    Route::put('serviceDetail/{id}', 'ServiceDetailController@update');
    Route::delete('serviceDetail/{id}', 'ServiceDetailController@destroy');

    //Inventory
    Route::get('inventoryList', 'InventoryController@index');
    Route::post('inventory', 'InventoryController@store');
    Route::get('getInventoryInfo/{id}', 'InventoryController@getInfo');
    Route::put('inventory/{id}', 'InventoryController@update');
    Route::delete('inventory/{id}', 'InventoryController@destroy');

    //InventoryDetail
    Route::get('inventoryDetailList', 'InventoryDetailController@index');
    Route::post('inventoryDetail', 'InventoryDetailController@store');
    Route::get('getInventoryDetailInfo/{id}', 'InventoryDetailController@getInfo');
    Route::put('inventoryDetail/{id}', 'InventoryDetailController@update');
    Route::delete('inventoryDetail/{id}', 'InventoryDetailController@destroy');

    /*
    Route::get('all_roles', 'RolesController@allRoles');
    Route::delete('roles/{role}', 'RolesController@destroy');*/
    /*
    Route::post('user', 'UserController@store');
    Route::get('user/{id}/edit', 'UserController@edit');
    Route::put('user/{id}', 'UserController@update');
    Route::delete('user/{id}', 'UserController@destroy');
    Route::post('/user/{id}/status', 'UserController@status');

    // Article
    Route::get('article', 'ArticleController@index')->name('api.article.index')->middleware(['permission:list_article']);
    Route::post('article', 'ArticleController@store')->name('api.article.store')->middleware(['permission:create_article']);
    Route::get('article/{id}/edit', 'ArticleController@edit')->name('api.article.edit')->middleware(['permission:update_article']);
    Route::put('article/{id}', 'ArticleController@update')->name('api.article.update')->middleware(['permission:update_article']);
    Route::delete('article/{id}', 'ArticleController@destroy')->name('api.article.destroy')->middleware(['permission:destroy_article']);*/

    
});

/*Route::group([
    'namespace' => 'Api',
], function () {
    // File Upload
    Route::post('file/upload', 'UploadController@fileUpload')->middleware('auth:api');
    // Edit Avatar
    Route::post('crop/avatar', 'UserController@cropAvatar')->middleware('auth:api');

    // Comment
    Route::get('commentable/{commentableId}/comment', 'CommentController@show')->middleware('api');
    Route::post('comments', 'CommentController@store')->middleware('auth:api');
    Route::delete('comments/{id}', 'CommentController@destroy')->middleware('auth:api');
    Route::post('comments/vote/{type}', 'MeController@postVoteComment')->middleware('auth:api');
    Route::get('tags', 'TagController@getList');
});*/


Route::group(['middleware' => 'jwt.auth' ,'namespace' => 'Auth'], function(){
  Route::get('auth/getUser', 'LoginController@getUser');
});
Route::group(['middleware' => 'jwt.refresh'], function(){
  Route::get('auth/userRefresh', 'LoginController@userRefresh');
});
