<?php
/**
 * Created by wcg.
 * User: milan011@sina.com
 * Date: 2018-10-08
 * Time: 22:04
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind('App\Repositories\Contracts\UserRepositoryInterface','App\Repositories\UserRepository');
        $this->app->bind('App\Repositories\User\UserRepositoryInterface', 'App\Repositories\User\UserRepository');
        $this->app->bind('App\Repositories\TaxCategories\TaxCategoriesRepositoryInterface', 'App\Repositories\TaxCategories\TaxCategoriesRepository');
        $this->app->bind('App\Repositories\TaxProvinces\TaxProvincesRepositoryInterface', 'App\Repositories\TaxProvinces\TaxProvincesRepository');
        $this->app->bind('App\Repositories\TaxGroups\TaxGroupsRepositoryInterface', 'App\Repositories\TaxGroups\TaxGroupsRepository');
        $this->app->bind('App\Repositories\TaxAuthorities\TaxAuthoritiesRepositoryInterface', 'App\Repositories\TaxAuthorities\TaxAuthoritiesRepository');
        $this->app->bind('App\Repositories\TaxGroupTaxes\TaxGroupTaxesRepositoryInterface', 'App\Repositories\TaxGroupTaxes\TaxGroupTaxesRepository');
        $this->app->bind('App\Repositories\ChartMaster\ChartMasterRepositoryInterface', 'App\Repositories\ChartMaster\ChartMasterRepository');
        $this->app->bind('App\Repositories\PeriodsInquiry\PeriodsInquiryRepositoryInterface', 'App\Repositories\PeriodsInquiry\PeriodsInquiryRepository');
        $this->app->bind('App\Repositories\SaleType\SaleTypeRepositoryInterface', 'App\Repositories\SaleType\SaleTypeRepository');
        $this->app->bind('App\Repositories\CustomerType\CustomerTypeRepositoryInterface', 'App\Repositories\CustomerType\CustomerTypeRepository');
        $this->app->bind('App\Repositories\SupplierType\SupplierTypeRepositoryInterface', 'App\Repositories\SupplierType\SupplierTypeRepository');
        $this->app->bind('App\Repositories\CreditStatus\CreditStatusRepositoryInterface', 'App\Repositories\CreditStatus\CreditStatusRepository');
        $this->app->bind('App\Repositories\PaymentTerm\PaymentTermRepositoryInterface', 'App\Repositories\PaymentTerm\PaymentTermRepository');
        $this->app->bind('App\Repositories\Currencies\CurrenciesRepositoryInterface', 'App\Repositories\Currencies\CurrenciesRepository');
        $this->app->bind('App\Repositories\PurchorderAuth\PurchorderAuthRepositoryInterface', 'App\Repositories\PurchorderAuth\PurchorderAuthRepository');
        $this->app->bind('App\Repositories\PaymentMethod\PaymentMethodRepositoryInterface', 'App\Repositories\PaymentMethod\PaymentMethodRepository');
        $this->app->bind('App\Repositories\SalesMan\SalesManRepositoryInterface', 'App\Repositories\SalesMan\SalesManRepository');
        $this->app->bind('App\Repositories\Area\AreaRepositoryInterface', 'App\Repositories\Area\AreaRepository');
        $this->app->bind('App\Repositories\Shipper\ShipperRepositoryInterface', 'App\Repositories\Shipper\ShipperRepository');
        $this->app->bind('App\Repositories\SalesGLPosting\SalesGLPostingRepositoryInterface', 'App\Repositories\SalesGLPosting\SalesGLPostingRepository');
        $this->app->bind('App\Repositories\StockCategory\StockCategoryRepositoryInterface', 'App\Repositories\StockCategory\StockCategoryRepository');
        $this->app->bind('App\Repositories\COGSGLPosting\COGSGLPostingRepositoryInterface', 'App\Repositories\COGSGLPosting\COGSGLPostingRepository');
        $this->app->bind('App\Repositories\FreightCost\FreightCostRepositoryInterface', 'App\Repositories\FreightCost\FreightCostRepository');
        $this->app->bind('App\Repositories\Locations\LocationsRepositoryInterface', 'App\Repositories\Locations\LocationsRepository');
        $this->app->bind('App\Repositories\DiscountMatrix\DiscountMatrixRepositoryInterface', 'App\Repositories\DiscountMatrix\DiscountMatrixRepository');
        $this->app->bind('App\Repositories\StockCatProperties\StockCatPropertiesRepositoryInterface', 'App\Repositories\StockCatProperties\StockCatPropertiesRepository');
        $this->app->bind('App\Repositories\DebtorsMaster\DebtorsMasterRepositoryInterface', 'App\Repositories\DebtorsMaster\DebtorsMasterRepository');
        $this->app->bind('App\Repositories\Custbranch\CustbranchRepositoryInterface', 'App\Repositories\Custbranch\CustbranchRepository');
        $this->app->bind('App\Repositories\UnitsOfMeasure\UnitsOfMeasureRepositoryInterface', 'App\Repositories\UnitsOfMeasure\UnitsOfMeasureRepository');
        $this->app->bind('App\Repositories\MRPCalendar\MRPCalendarRepositoryInterface', 'App\Repositories\MRPCalendar\MRPCalendarRepository');
        $this->app->bind('App\Repositories\MRPDemandType\MRPDemandTypeRepositoryInterface', 'App\Repositories\MRPDemandType\MRPDemandTypeRepository');
        $this->app->bind('App\Repositories\Department\DepartmentRepositoryInterface', 'App\Repositories\Department\DepartmentRepository');
        $this->app->bind('App\Repositories\PcTypeTab\PcTypeTabRepositoryInterface', 'App\Repositories\PcTypeTab\PcTypeTabRepository');
        $this->app->bind('App\Repositories\PcTab\PcTabRepositoryInterface', 'App\Repositories\PcTab\PcTabRepository');
        $this->app->bind('App\Repositories\PcExpenses\PcExpensesRepositoryInterface', 'App\Repositories\PcExpenses\PcExpensesRepository');
        $this->app->bind('App\Repositories\Tags\TagsRepositoryInterface', 'App\Repositories\Tags\TagsRepository');
        $this->app->bind('App\Repositories\PcAssignCashToTab\PcAssignCashToTabRepositoryInterface', 'App\Repositories\PcAssignCashToTab\PcAssignCashToTabRepository');
        $this->app->bind('App\Repositories\PcClaimExpensesFromTab\PcClaimExpensesFromTabRepositoryInterface', 'App\Repositories\PcClaimExpensesFromTab\PcClaimExpensesFromTabRepository');
        $this->app->bind('App\Repositories\PcAuthorizeExpense\PcAuthorizeExpenseRepositoryInterface', 'App\Repositories\PcAuthorizeExpense\PcAuthorizeExpenseRepository');
        $this->app->bind('App\Repositories\BankTrans\BankTransRepositoryInterface', 'App\Repositories\BankTrans\BankTransRepository');
        $this->app->bind('App\Repositories\Gltrans\GltransRepositoryInterface', 'App\Repositories\Gltrans\GltransRepository');
        $this->app->bind('App\Repositories\SysType\SysTypeRepositoryInterface', 'App\Repositories\SysType\SysTypeRepository');
        $this->app->bind('App\Repositories\FixedAssetCategorie\FixedAssetCategorieRepositoryInterface', 'App\Repositories\FixedAssetCategorie\FixedAssetCategorieRepository');
        $this->app->bind('App\Repositories\Company\CompanyRepositoryInterface', 'App\Repositories\Company\CompanyRepository');
        $this->app->bind('App\Repositories\FixedAssets\FixedAssetsRepositoryInterface', 'App\Repositories\FixedAssets\FixedAssetsRepository');
        $this->app->bind('App\Repositories\FixedAssetLocation\FixedAssetLocationRepositoryInterface', 'App\Repositories\FixedAssetLocation\FixedAssetLocationRepository');
        $this->app->bind('App\Repositories\MaintenanceTask\MaintenanceTaskRepositoryInterface', 'App\Repositories\MaintenanceTask\MaintenanceTaskRepository');
        $this->app->bind('App\Repositories\FixedAssetItem\FixedAssetItemRepositoryInterface', 'App\Repositories\FixedAssetItem\FixedAssetItemRepository');
        $this->app->bind('App\Repositories\AccountSection\AccountSectionRepositoryInterface', 'App\Repositories\AccountSection\AccountSectionRepository');
        $this->app->bind('App\Repositories\AccountGroup\AccountGroupRepositoryInterface', 'App\Repositories\AccountGroup\AccountGroupRepository');
    }
}
