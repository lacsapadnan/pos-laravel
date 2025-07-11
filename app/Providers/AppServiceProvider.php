<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\Interface\CategoryRepositoryInterface;
use App\Repositories\Interface\CustomerRepositoryInterface;
use App\Repositories\Interface\ProductRepositoryInterface;
use App\Repositories\Interface\RoleRepositoryInterface;
use App\Repositories\Interface\SettingRepositoryInterface;
use App\Repositories\Interface\StockMovementRepositoryInterface;
use App\Repositories\Interface\StockOpnameRepositoryInterface;
use App\Repositories\Interface\SupplierRepositoryInterface;
use App\Repositories\Interface\UnitRepositoryInterface;
use App\Repositories\Interface\UserRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Repositories\RoleRepository;
use App\Repositories\SettingRepository;
use App\Repositories\StockMovementRepository;
use App\Repositories\StockOpnameRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\UnitRepository;
use App\Repositories\UserRepository;
use App\Services\CategoryService;
use App\Services\CustomerService;
use App\Services\Interface\CategoryServiceInterface;
use App\Services\Interface\CustomerServiceInterface;
use App\Services\Interface\ProductServiceInterface;
use App\Services\Interface\RoleServiceInterface;
use App\Services\Interface\NotificationServiceInterface;
use App\Services\Interface\SettingServiceInterface;
use App\Services\Interface\StockOpnameServiceInterface;
use App\Services\Interface\StockServiceInterface;
use App\Services\Interface\SupplierServiceInterface;
use App\Services\Interface\UnitServiceInterface;
use App\Services\Interface\UserServiceInterface;
use App\Services\NotificationService;
use App\Services\ProductService;
use App\Services\RoleService;
use App\Services\SettingService;
use App\Services\StockOpnameService;
use App\Services\StockService;
use App\Services\SupplierService;
use App\Services\UnitService;
use App\Services\UserService;
use App\View\Composers\NotificationComposer;
use App\View\Composers\SettingComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $bindings = [
            RoleServiceInterface::class => RoleService::class,
            RoleRepositoryInterface::class => RoleRepository::class,
            CategoryServiceInterface::class => CategoryService::class,
            CategoryRepositoryInterface::class => CategoryRepository::class,
            SupplierServiceInterface::class => SupplierService::class,
            SupplierRepositoryInterface::class => SupplierRepository::class,
            CustomerServiceInterface::class => CustomerService::class,
            CustomerRepositoryInterface::class => CustomerRepository::class,
            UnitServiceInterface::class => UnitService::class,
            UnitRepositoryInterface::class => UnitRepository::class,
            ProductServiceInterface::class => ProductService::class,
            ProductRepositoryInterface::class => ProductRepository::class,
            StockMovementRepositoryInterface::class => StockMovementRepository::class,
            StockOpnameRepositoryInterface::class => StockOpnameRepository::class,
            StockServiceInterface::class => StockService::class,
            StockOpnameServiceInterface::class => StockOpnameService::class,
            NotificationServiceInterface::class => NotificationService::class,
            SettingServiceInterface::class => SettingService::class,
            SettingRepositoryInterface::class => SettingRepository::class,
            UserServiceInterface::class => UserService::class,
            UserRepositoryInterface::class => UserRepository::class,
        ];

        foreach ($bindings as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register view composer for notifications
        View::composer('partials.navbar', NotificationComposer::class);

        // Register view composer for settings
        View::composer('partials.sidebar', SettingComposer::class);
    }
}
