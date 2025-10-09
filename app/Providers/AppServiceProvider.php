<?php

namespace App\Providers;

use App\Modules\Category\Domain\Interfaces\CategoryRepositoryInterface;
use App\Modules\Category\Infrastructure\Persistence\EloquentCategoryRepository;
use App\Modules\MeasurementUnit\Domain\Interfaces\MeasurementUnitRepositoryInterface;
use App\Modules\MeasurementUnit\Infrastructure\Persistence\EloquentMeasurementUnitRepository;
use App\Modules\Menu\Domain\Interfaces\MenuRepositoryInterface;
use App\Modules\Menu\Domain\Services\UserMenuService;
use App\Modules\Menu\Infrastructure\Persistence\EloquentMenuRepository;
use App\Modules\PercentageIGV\Domain\Interfaces\PercentageIGVRepositoryInterface;
use App\Modules\PercentageIGV\Infrastructure\Persistence\EloquentPercentageIGVRepository;
use App\Modules\SubCategory\Domain\Interfaces\SubCategoryRepositoryInterface;
use App\Modules\SubCategory\Infrastructure\Persistence\EloquentSubCategoryRepository;
use App\Modules\TransportCompany\Domain\Interfaces\TransportCompanyRepositoryInterface;
use App\Modules\TransportCompany\Infrastructure\Persistence\EloquentTransportCompanyRepository;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use App\Modules\User\Infrastructure\Persistence\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        //$this->app->bind(MenuRepositoryInterface::class, EloquentMenuRepository::class);
        $this->app->bind(UserMenuService::class);
        $this->app->bind(TransportCompanyRepositoryInterface::class, EloquentTransportCompanyRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, EloquentCategoryRepository::class);
        $this->app->bind(SubCategoryRepositoryInterface::class, EloquentSubCategoryRepository::class);
        $this->app->bind(PercentageIGVRepositoryInterface::class, EloquentPercentageIGVRepository::class);
        $this->app->bind(MeasurementUnitRepositoryInterface::class, EloquentMeasurementUnitRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
