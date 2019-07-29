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

    // PeriodsInquiry
    Route::get('periodsInquiryList', 'PeriodsInquiryController@index');

    // SaleType
    Route::get('saleTypeList', 'SaleTypeController@index');
    Route::get('saleType/{id}', 'SaleTypeController@show');
    Route::post('saleType', 'SaleTypeController@store');
    Route::put('saleType/{id}', 'SaleTypeController@update');
    Route::delete('saleType/{id}', 'SaleTypeController@destroy');

    // CustomerType
    Route::get('customerTypeList', 'CustomerTypeController@index');
    Route::get('customerType/{id}', 'CustomerTypeController@show');
    Route::post('customerType', 'CustomerTypeController@store');
    Route::put('customerType/{id}', 'CustomerTypeController@update');
    Route::delete('customerType/{id}', 'CustomerTypeController@destroy');

    // SupplierType
    Route::get('supplierTypeList', 'SupplierTypeController@index');
    Route::get('supplierType/{id}', 'SupplierTypeController@show');
    Route::post('supplierType', 'SupplierTypeController@store');
    Route::put('supplierType/{id}', 'SupplierTypeController@update');
    Route::delete('supplierType/{id}', 'SupplierTypeController@destroy');

    // CreditStatus
    Route::get('creditStatusList', 'CreditStatusController@index');
    Route::get('creditStatus/{id}', 'CreditStatusController@show');
    Route::post('creditStatus', 'CreditStatusController@store');
    Route::put('creditStatus/{id}', 'CreditStatusController@update');
    Route::delete('creditStatus/{id}', 'CreditStatusController@destroy');

    // PaymentTerm
    Route::get('paymentTermList', 'PaymentTermController@index');
    Route::get('paymentTerm/{id}', 'PaymentTermController@show');
    Route::post('paymentTerm', 'PaymentTermController@store');
    Route::put('paymentTerm/{id}', 'PaymentTermController@update');
    Route::delete('paymentTerm/{id}', 'PaymentTermController@destroy');

    //PurchorderAuth
    Route::get('purchorderAuthList', 'PurchorderAuthController@index');
    Route::get('purchorderAuth/{id}', 'PurchorderAuthController@show');
    Route::post('purchorderAuth', 'PurchorderAuthController@store');
    Route::put('purchorderAuth/{id}', 'PurchorderAuthController@update');
    Route::delete('purchorderAuth/{id}', 'PurchorderAuthController@destroy');

    // currencies
    Route::get('currenciesList', 'CurrenciesController@index');
    Route::get('currencies/{id}', 'CurrenciesController@show');
    Route::post('currencies', 'CurrenciesController@store');
    Route::put('currencies/{id}', 'CurrenciesController@update');
    Route::delete('currencies/{id}', 'CurrenciesController@destroy');

    // PaymentMethod
    Route::get('paymentMethodList', 'PaymentMethodController@index');
    Route::get('paymentMethod/{id}', 'PaymentMethodController@show');
    Route::post('paymentMethod', 'PaymentMethodController@store');
    Route::put('paymentMethod/{id}', 'PaymentMethodController@update');
    Route::delete('paymentMethod/{id}', 'PaymentMethodController@destroy');

    // SalesMan
    Route::get('salesManList', 'SalesManController@index');
    Route::get('salesMan/{id}', 'SalesManController@show');
    Route::post('salesMan', 'SalesManController@store');
    Route::put('salesMan/{id}', 'SalesManController@update');
    Route::delete('salesMan/{id}', 'SalesManController@destroy');

    // Area
    Route::get('areaList', 'AreaController@index');
    Route::get('area/{id}', 'AreaController@show');
    Route::post('area', 'AreaController@store');
    Route::put('area/{id}', 'AreaController@update');
    Route::delete('area/{id}', 'AreaController@destroy');

    // Shipper
    Route::get('shipperList', 'ShipperController@index');
    Route::get('shipper/{id}', 'ShipperController@show');
    Route::post('shipper', 'ShipperController@store');
    Route::put('shipper/{id}', 'ShipperController@update');
    Route::delete('shipper/{id}', 'ShipperController@destroy');

    // SalesGLPosting
    Route::get('salesGLPostingList', 'SalesGLPostingController@index');
    Route::get('salesGLPosting/{id}', 'SalesGLPostingController@show');
    Route::post('salesGLPosting', 'SalesGLPostingController@store');
    Route::put('salesGLPosting/{id}', 'SalesGLPostingController@update');
    Route::delete('salesGLPosting/{id}', 'SalesGLPostingController@destroy');

    // StockCategory
    Route::get('stockCategoryList', 'StockCategoryController@index');
    Route::get('stockCategory/{id}', 'StockCategoryController@show');
    Route::post('stockCategory', 'StockCategoryController@store');
    Route::put('stockCategory/{id}', 'StockCategoryController@update');
    Route::delete('stockCategory/{id}', 'StockCategoryController@destroy');
});

Route::group(['middleware' => 'jwt.auth', 'namespace' => 'Auth'], function () {
    Route::get('auth/getUser', 'LoginController@getUser');
});
Route::group(['middleware' => 'jwt.refresh'], function () {
    Route::get('auth/userRefresh', 'LoginController@userRefresh');
});
