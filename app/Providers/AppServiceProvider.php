<?php

namespace App\Providers;

use App\Interfaces\Orders\OrderRepositoryInterface;
use App\Models\Order;
use App\Observers\OrderObserver;
use App\Repositories\Orders\OrderRepository;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;

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

        Filament::registerNavigationGroups([
            'Orders',
            'Products - units',
            'Categories',
            'Branches',
            'User & Roles',
        ]);
    }
}
