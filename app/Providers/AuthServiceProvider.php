<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderTransfer;
use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use App\Policies\BranchPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\OrderPolicy;
use App\Policies\OrderTransferPolicy;
use App\Policies\ProductPolicy;
use App\Policies\UnitPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'Spatie\Permission\Models\Role' => 'App\Policies\RolePolicy',
        Order::class => OrderPolicy::class,
        OrderTransfer::class => OrderTransferPolicy::class,
        Branch::class => BranchPolicy::class,
        Category::class => CategoryPolicy::class,
        Product::class => ProductPolicy::class,
        Unit::class => UnitPolicy::class,
        User::class => UserPolicy::class,
        // 'Ramnzys\FilamentEmailLog\Models\Email' => 'App\Policies\EmailPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
