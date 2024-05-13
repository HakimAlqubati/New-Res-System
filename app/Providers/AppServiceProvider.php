<?php

namespace App\Providers;

use App\Filament\Reports\Orders\OrderReportResource;
use App\Filament\Resources\BranchResource;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\OrderReportsResource\GeneralReportOfProductsResource;
use App\Filament\Resources\OrderReportsResource\ReportProductQuantitiesResource;
use App\Filament\Resources\OrderResource;
use App\Filament\Resources\ProductResource;
use App\Filament\Resources\PurchaseInvoiceResource;
use App\Filament\Resources\Reports\BranchStoreReportResource;
use App\Filament\Resources\Reports\PurchaseInvoiceReportResource;
use App\Filament\Resources\Reports\StoresReportResource;
use App\Filament\Resources\Shield\RoleResource;
use App\Filament\Resources\StockReportResource;
use App\Filament\Resources\StoreResource;
use App\Filament\Resources\SupplierResource;
use App\Filament\Resources\SystemSettingResource;
use App\Filament\Resources\TransferOrderResource;
use App\Filament\Resources\UnitResource;
use App\Filament\Resources\UserResource;
use App\Interfaces\Orders\OrderRepositoryInterface;
use App\Models\Order;
use App\Observers\OrderObserver;
use App\Repositories\Orders\OrderRepository;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            OrderRepositoryInterface::class,
            OrderRepository::class,
        );
    }



    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Order::observe(OrderObserver::class);

        // Filament::registerNavigationGroups([
        //     'Orders',
        //     'Products - units',
        //     'Categories',
        //     'Branches',
        //     'User & Roles',
        // ]);

        Filament::navigation(function (NavigationBuilder $builder): NavigationBuilder {
            $menu =  $builder->items([
                NavigationItem::make(__('lang.dashboard'))
                    ->icon('heroicon-o-home')
                    ->activeIcon('heroicon-s-home')
                    ->isActiveWhen(fn (): bool => request()->routeIs('filament.pages.dashboard'))
                    ->url(route('filament.pages.dashboard')),
            ])
                ->groups([
                    NavigationGroup::make(__('lang.orders'))
                        ->items([
                            ...OrderResource::getNavigationItems(),
                            ...TransferOrderResource::getNavigationItems(),
                        ]),
                ])
                ->groups([
                    NavigationGroup::make(__('lang.order_reports'))
                        ->items([
                            ...ReportProductQuantitiesResource::getNavigationItems(),
                            ...GeneralReportOfProductsResource::getNavigationItems(),
                        ]),
                ])
                ->groups([
                    NavigationGroup::make(__('lang.products_and_units'))
                        ->items([
                            ...ProductResource::getNavigationItems(),
                            ...UnitResource::getNavigationItems(),
                        ]),
                ])
                ->groups([
                    NavigationGroup::make(__('lang.categories'))
                        ->items([
                            ...CategoryResource::getNavigationItems(),
                        ]),
                ])
                ->groups([
                    NavigationGroup::make(__('lang.branches'))
                        ->items([
                            ...BranchResource::getNavigationItems(),
                        ]),
                ]);


            $menu = $builder->groups([
                NavigationGroup::make(__('lang.user_and_roles'))
                    ->items([
                        ...UserResource::getNavigationItems(),
                        ...(  RoleResource::getNavigationItems()  )
                    ]),
            ]);


            $menu = $builder->groups([
                NavigationGroup::make(__('lang.inventory_management'))
                    ->items([
                        ...SupplierResource::getNavigationItems(),
                        ...PurchaseInvoiceResource::getNavigationItems(),
                        ...PurchaseInvoiceReportResource::getNavigationItems(),
                        ...StoreResource::getNavigationItems(),
                        ...StoresReportResource::getNavigationItems(),
                        ...BranchStoreReportResource::getNavigationItems(),

                    ]),
            ]);


            if (getCurrentRole() == 1) {
                $menu = $builder->groups([
                    NavigationGroup::make(__('system_settings.system_settings'))
                        ->items([
                            ...SystemSettingResource::getNavigationItems(),
                        ]),
                ]);
            }
                // ->groups([
                // NavigationGroup::make(__('lang.reports'))
                // ->items([
                // ...OrderReportResource::getNavigationItems(), 
                // ]),
                // ])
            ;
            return $menu;
        });

        Filament::serving(function () {
            // Filament::registerTheme(
            // mix('css/filament.css'),
            // );
        });

        Filament::registerStyles([
            asset("filament/main.css"),
            asset("New-Res-System/public/filament/main.css"),
        ]);
    }
}
