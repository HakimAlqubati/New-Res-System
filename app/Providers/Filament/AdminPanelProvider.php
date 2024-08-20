<?php

namespace App\Providers\Filament;

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
use App\Filament\Resources\StoreResource;
use App\Filament\Resources\SupplierResource;
use App\Filament\Resources\SystemSettingResource;
use App\Filament\Resources\TestinfoList\TestinfoListResource;
use App\Filament\Resources\TransferOrderResource;
use App\Filament\Resources\UnitResource;
use App\Filament\Resources\UserResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
        ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
            $menu =  $builder->items([
                NavigationItem::make(__('lang.dashboard'))
                    ->icon('heroicon-o-home')
                    ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
                    ->url(fn (): string => Dashboard::getUrl()),
                // ...UserResource::getNavigationItems(),
                // ...Settings::getNavigationItems(),
                
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
                        ...(RoleResource::canViewAny() ? RoleResource::getNavigationItems() : [])
                    ]),
            ]);

            if (getCurrentRole() == 1) {
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
            }

            if (getCurrentRole() == 1) {
                $menu = $builder->groups([
                    NavigationGroup::make(__('system_settings.system_settings'))
                        ->items([
                            ...SystemSettingResource::getNavigationItems(),
                            ...TestinfoListResource::getNavigationItems(),
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
            ;
            return $menu;
        })
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->databaseNotifications()
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->topNavigation()
            ->maxContentWidth('full')
            ;
    }
}