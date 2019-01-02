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
    $this->app->bind('App\Repositories\User\UserRepositoryInterface','App\Repositories\User\UserRepository');
    $this->app->bind('App\Repositories\Manager\ManagerRepositoryInterface','App\Repositories\Manager\ManagerRepository');
    $this->app->bind('App\Repositories\Package\PackageRepositoryInterface','App\Repositories\Package\PackageRepository');
    $this->app->bind('App\Repositories\InfoSelf\InfoSelfRepositoryInterface','App\Repositories\InfoSelf\InfoSelfRepository');
    $this->app->bind('App\Repositories\InfoDianxin\InfoDianxinRepositoryInterface','App\Repositories\InfoDianxin\InfoDianxinRepository');
    $this->app->bind('App\Repositories\PackageInfo\PackageInfoRepositoryInterface','App\Repositories\PackageInfo\PackageInfoRepository');
    $this->app->bind('App\Repositories\PackageInfo\PackageInfoRepositoryInterface','App\Repositories\PackageInfo\PackageInfoRepository');
    $this->app->bind('App\Repositories\Goods\GoodsRepositoryInterface','App\Repositories\Goods\GoodsRepository');
    $this->app->bind('App\Repositories\Inventory\InventoryRepositoryInterface','App\Repositories\Inventory\InventoryRepository');
    $this->app->bind('App\Repositories\InventoryDetail\InventoryDetailRepositoryInterface','App\Repositories\InventoryDetail\InventoryDetailRepository');
    $this->app->bind('App\Repositories\Service\ServiceRepositoryInterface','App\Repositories\Service\ServiceRepository');
    $this->app->bind('App\Repositories\ServiceDetail\ServiceDetailRepositoryInterface','App\Repositories\ServiceDetail\ServiceDetailRepository');
    $this->app->bind('App\Repositories\ServiceDetailGoods\ServiceDetailGoodsRepositoryInterface','App\Repositories\ServiceDetailGoods\ServiceDetailGoodsRepository');
  }
}
