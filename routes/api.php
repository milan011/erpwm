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

    //TaxCategories
    Route::get('taxCategoriesList', 'TaxCategoriesController@index');
    Route::get('taxCategories/{id}', 'TaxCategoriesController@show');
    Route::post('taxCategories', 'TaxCategoriesController@store');
    Route::put('taxCategories/{id}', 'TaxCategoriesController@update');
    Route::delete('taxCategories/{id}', 'TaxCategoriesController@destroy');

    //TaxProvinces
    Route::get('taxProvincesList', 'TaxProvincesController@index');
    Route::get('taxProvinces/{id}', 'TaxProvincesController@show');
    Route::post('taxProvinces', 'TaxProvincesController@store');
    Route::put('taxProvinces/{id}', 'TaxProvincesController@update');
    Route::delete('taxProvinces/{id}', 'TaxProvincesController@destroy');

    //TaxGroups
    Route::get('taxGroupsList', 'TaxGroupsController@index');
    Route::get('taxGroups/{id}', 'TaxGroupsController@show');
    Route::post('taxGroups', 'TaxGroupsController@store');
    Route::post('taxGroupAuthorities', 'TaxGroupsController@taxGroupAuthorities');
    Route::post('setTaxGroupAuthorities', 'TaxGroupsController@setTaxGroupAuthorities');
    Route::put('taxGroups/{id}', 'TaxGroupsController@update');
    Route::delete('taxGroups/{id}', 'TaxGroupsController@destroy');

    //TaxAuthorities
    Route::get('taxAuthoritiesList', 'TaxAuthoritiesController@index');
    Route::get('taxAuthorities/{id}', 'TaxAuthoritiesController@show');
    Route::post('getTaxRates', 'TaxAuthoritiesController@getTaxRates');
    Route::post('setTaxRates', 'TaxAuthoritiesController@setTaxRates');
    Route::post('taxAuthorities', 'TaxAuthoritiesController@store');
    Route::put('taxAuthorities/{id}', 'TaxAuthoritiesController@update');
    Route::delete('taxAuthorities/{id}', 'TaxAuthoritiesController@destroy');

    // ChartMaster chartMaster
    Route::get('chartMasterList', 'ChartMasterController@index');
    Route::get('chartMaster/{id}', 'ChartMasterController@show');
    Route::post('chartMaster', 'ChartMasterController@store');
    Route::put('chartMaster/{id}', 'ChartMasterController@update');
    Route::delete('chartMaster/{id}', 'ChartMasterController@destroy');

});

Route::group(['middleware' => 'jwt.auth', 'namespace' => 'Auth'], function () {
    Route::get('auth/getUser', 'LoginController@getUser');
});
Route::group(['middleware' => 'jwt.refresh'], function () {
    Route::get('auth/userRefresh', 'LoginController@userRefresh');
});
