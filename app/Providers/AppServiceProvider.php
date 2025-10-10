<?php

namespace App\Providers;

use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Branch\Infrastructure\Persistence\EloquentBranchRepository;
use App\Modules\Category\Domain\Interfaces\CategoryRepositoryInterface;
use App\Modules\Category\Infrastructure\Persistence\EloquentCategoryRepository;
use App\Modules\Menu\Domain\Interfaces\MenuRepositoryInterface;
use App\Modules\Menu\Domain\Services\UserMenuService;
use App\Modules\Menu\Infrastructure\Persistence\EloquentMenuRepository;
use App\Modules\RecordType\Domain\Interfaces\RecordTypeRepositoryInterface;
use App\Modules\RecordType\Infrastructure\Persistence\EloquentRecordTypeRepository;
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
        $this->app->bind(RecordTypeRepositoryInterface::class, EloquentRecordTypeRepository::class);
        $this->app->bind(BranchRepositoryInterface::class, EloquentBranchRepository::class);
        

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
