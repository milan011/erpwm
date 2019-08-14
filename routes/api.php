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
    Route::get('role/{role}/stockCategory', 'RoleController@getRoleStockCategory');
    Route::post('giveRole/{role}/stockCategory', 'RoleController@giveRoleStockCategory');

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

    // COGSGLPosting
    Route::get('cOGSGLPostingList', 'COGSGLPostingController@index');
    Route::get('cOGSGLPosting/{id}', 'COGSGLPostingController@show');
    Route::post('cOGSGLPosting', 'COGSGLPostingController@store');
    Route::put('cOGSGLPosting/{id}', 'COGSGLPostingController@update');
    Route::delete('cOGSGLPosting/{id}', 'COGSGLPostingController@destroy');

    // FreightCost
    Route::get('freightCostList', 'FreightCostController@index');
    Route::get('freightCost/{id}', 'FreightCostController@show');
    Route::post('freightCost', 'FreightCostController@store');
    Route::put('freightCost/{id}', 'FreightCostController@update');
    Route::delete('freightCost/{id}', 'FreightCostController@destroy');

    // Locations
    Route::get('locationsList', 'LocationsController@index');
    Route::get('locations/{id}', 'LocationsController@show');
    Route::post('locations', 'LocationsController@store');
    Route::put('locations/{id}', 'LocationsController@update');
    Route::delete('locations/{id}', 'LocationsController@destroy');

    // DiscountMatrix
    Route::get('discountMatrixList', 'DiscountMatrixController@index');
    Route::get('discountMatrix/{id}', 'DiscountMatrixController@show');
    Route::post('discountMatrix', 'DiscountMatrixController@store');
    Route::put('discountMatrix/{id}', 'DiscountMatrixController@update');
    Route::delete('discountMatrix/{id}', 'DiscountMatrixController@destroy');

    // StockCatProperties
    Route::get('stockCatPropertiesList', 'StockCatPropertiesController@index');
    Route::get('stockCatProperties/{id}', 'StockCatPropertiesController@show');
    Route::post('stockCatProperties', 'StockCatPropertiesController@store');
    Route::put('stockCatProperties/{id}', 'StockCatPropertiesController@update');
    Route::delete('stockCatProperties/{id}', 'StockCatPropertiesController@destroy');

    // DebtorsMaster
    Route::get('debtorsMasterList', 'DebtorsMasterController@index');
    Route::get('debtorsMaster/{id}', 'DebtorsMasterController@show');
    Route::post('debtorsMaster', 'DebtorsMasterController@store');
    Route::put('debtorsMaster/{id}', 'DebtorsMasterController@update');
    Route::delete('debtorsMaster/{id}', 'DebtorsMasterController@destroy');

    // Custbranch
    Route::get('custbranchList', 'CustbranchController@index');
    Route::get('custbranch/{id}', 'CustbranchController@show');
    Route::post('custbranch', 'CustbranchController@store');
    Route::put('custbranch/{id}', 'CustbranchController@update');
    Route::delete('custbranch/{id}', 'CustbranchController@destroy');

    // UnitsOfMeasure
    Route::get('unitsOfMeasureList', 'UnitsOfMeasureController@index');
    Route::get('unitsOfMeasure/{id}', 'UnitsOfMeasureController@show');
    Route::post('unitsOfMeasure', 'UnitsOfMeasureController@store');
    Route::put('unitsOfMeasure/{id}', 'UnitsOfMeasureController@update');
    Route::delete('unitsOfMeasure/{id}', 'UnitsOfMeasureController@destroy');

    // MRPCalendar
    Route::get('mRPCalendarList', 'MRPCalendarController@index');
    Route::get('mRPCalendar/{id}', 'MRPCalendarController@show');
    Route::post('mRPCalendar', 'MRPCalendarController@store');
    Route::put('mRPCalendar/{id}', 'MRPCalendarController@update');
    Route::delete('mRPCalendar/{id}', 'MRPCalendarController@destroy');

    // MRPDemandType
    Route::get('mRPDemandTypeList', 'MRPDemandTypeController@index');
    Route::get('mRPDemandType/{id}', 'MRPDemandTypeController@show');
    Route::post('mRPDemandType', 'MRPDemandTypeController@store');
    Route::put('mRPDemandType/{id}', 'MRPDemandTypeController@update');
    Route::delete('mRPDemandType/{id}', 'MRPDemandTypeController@destroy');

    // Department
    Route::get('departmentList', 'DepartmentController@index');
    Route::get('department/{id}', 'DepartmentController@show');
    Route::post('department', 'DepartmentController@store');
    Route::put('department/{id}', 'DepartmentController@update');
    Route::delete('department/{id}', 'DepartmentController@destroy');

    // PcTypeTab
    Route::get('pcTypeTabList', 'PcTypeTabController@index');
    Route::get('pcTypeTab/{id}', 'PcTypeTabController@show');
    Route::post('pcTypeTab', 'PcTypeTabController@store');
    Route::put('pcTypeTab/{id}', 'PcTypeTabController@update');
    Route::delete('pcTypeTab/{id}', 'PcTypeTabController@destroy');
    Route::get('type/{type}/pcExpenses', 'PcTypeTabController@getTypePcExpenses');
    Route::post('giveType/{type}/pcExpenses', 'PcTypeTabController@giveTypePcExpenses');

    // PcTab
    Route::get('pcTabList', 'PcTabController@index');
    Route::get('pcTab/{id}', 'PcTabController@show');
    Route::post('pcTab', 'PcTabController@store');
    Route::put('pcTab/{id}', 'PcTabController@update');
    Route::delete('pcTab/{id}', 'PcTabController@destroy');

    // PcExpenses
    Route::get('pcExpensesList', 'PcExpensesController@index');
    Route::get('pcExpenses/{id}', 'PcExpensesController@show');
    Route::post('pcExpenses', 'PcExpensesController@store');
    Route::put('pcExpenses/{id}', 'PcExpensesController@update');
    Route::delete('pcExpenses/{id}', 'PcExpensesController@destroy');

    // Tags
    Route::get('tagsList', 'TagsController@index');
    Route::get('tags/{id}', 'TagsController@show');
    Route::post('tags', 'TagsController@store');
    Route::put('tags/{id}', 'TagsController@update');
    Route::delete('tags/{id}', 'TagsController@destroy');

    // PcAssignCashToTab
    Route::get('pcAssignCashToTabList', 'PcAssignCashToTabController@index');
    Route::get('pcAssignCashToTab/{id}', 'PcAssignCashToTabController@show');
    Route::post('pcAssignCashToTab', 'PcAssignCashToTabController@store');
    Route::put('pcAssignCashToTab/{id}', 'PcAssignCashToTabController@update');
    Route::delete('pcAssignCashToTab/{id}', 'PcAssignCashToTabController@destroy');

    // PcClaimExpensesFromTab   getExpenses
    Route::get('pcClaimExpensesFromTabList', 'PcClaimExpensesFromTabController@index');
    Route::get('pcClaimExpensesFromTab/{id}', 'PcClaimExpensesFromTabController@show');
    Route::get('getExpenses', 'PcClaimExpensesFromTabController@getExpenses');
    Route::post('pcClaimExpensesFromTab', 'PcClaimExpensesFromTabController@store');
    Route::put('pcClaimExpensesFromTab/{id}', 'PcClaimExpensesFromTabController@update');
    Route::delete('pcClaimExpensesFromTab/{id}', 'PcClaimExpensesFromTabController@destroy');

    // PcAuthorizeExpense
    Route::get('pcAuthorizeExpenseList', 'PcAuthorizeExpenseController@index');
    Route::get('pcAuthorizeExpense/{id}', 'PcAuthorizeExpenseController@show');
    Route::post('pcAuthorizeExpense', 'PcAuthorizeExpenseController@store');
    Route::put('pcAuthorizeExpense/{id}', 'PcAuthorizeExpenseController@update');
    Route::post('approvalAuthorizeExpense/{id}', 'PcAuthorizeExpenseController@approval');

    // BankTrans
    Route::get('bankTransList', 'BankTransController@index');
    Route::get('bankTrans/{id}', 'BankTransController@show');
    Route::post('bankTrans', 'BankTransController@store');
    Route::put('bankTrans/{id}', 'BankTransController@update');
    Route::delete('bankTrans/{id}', 'BankTransController@destroy');

    // Gltrans
    Route::get('gltransList', 'GltransController@index');
    Route::get('gltrans/{id}', 'GltransController@show');
    Route::post('gltrans', 'GltransController@store');
    Route::put('gltrans/{id}', 'GltransController@update');
    Route::delete('gltrans/{id}', 'GltransController@destroy');

    // SysType
    Route::get('sysTypeList', 'SysTypeController@index');
    Route::get('sysType/{id}', 'SysTypeController@show');
    Route::post('sysType', 'SysTypeController@store');
    Route::put('sysType/{id}', 'SysTypeController@update');
    Route::delete('sysType/{id}', 'SysTypeController@destroy');

    // FixedAssetCategorie
    Route::get('fixedAssetCategorieList', 'FixedAssetCategorieController@index');
    Route::get('fixedAssetCategorie/{id}', 'FixedAssetCategorieController@show');
    Route::post('fixedAssetCategorie', 'FixedAssetCategorieController@store');
    Route::put('fixedAssetCategorie/{id}', 'FixedAssetCategorieController@update');
    Route::delete('fixedAssetCategorie/{id}', 'FixedAssetCategorieController@destroy');

    // Company
    Route::get('companyList', 'CompanyController@index');
    Route::get('company/{id}', 'CompanyController@show');
    Route::post('company', 'CompanyController@store');
    Route::put('company/{id}', 'CompanyController@update');
    Route::delete('company/{id}', 'CompanyController@destroy');

    // FixedAssets
    Route::get('fixedAssetsList', 'FixedAssetsController@index');
    Route::get('fixedAssets/{id}', 'FixedAssetsController@show');
    Route::post('fixedAssets', 'FixedAssetsController@store');
    Route::put('fixedAssets/{id}', 'FixedAssetsController@update');
    Route::delete('fixedAssets/{id}', 'FixedAssetsController@destroy');
});

Route::group(['middleware' => 'jwt.auth', 'namespace' => 'Auth'], function () {
    Route::get('auth/getUser', 'LoginController@getUser');
});
Route::group(['middleware' => 'jwt.refresh'], function () {
    Route::get('auth/userRefresh', 'LoginController@userRefresh');
});
