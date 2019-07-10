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
    'namespace'  => 'Api',
], function () {
    // User
    Route::get('getUserInfo', 'UserController@getUser');
    Route::get('userAll', 'UserController@userAll');
    Route::post('userLogout', 'UserController@logout');
    Route::get('userList', 'UserController@index');
    Route::post('user', 'UserController@store');
    Route::post('passReset', 'UserController@passReset');
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

    //TaxCategores
    Route::get('taxCategoriesList', 'TaxCategoriesController@index');
    Route::get('taxCategories/{id}', 'TaxCategoriesController@show');
    Route::post('taxCategoires', 'TaxCategoriesController@store');
    Route::put('taxCategories/{id}', 'TaxCategoriesController@update');
    Route::delete('taxCategoires/{id}', 'TaxCategoriesController@destroy');

});

Route::group(['middleware' => 'jwt.auth', 'namespace' => 'Auth'], function () {
    Route::get('auth/getUser', 'LoginController@getUser');
});
Route::group(['middleware' => 'jwt.refresh'], function () {
    Route::get('auth/userRefresh', 'LoginController@userRefresh');
});
