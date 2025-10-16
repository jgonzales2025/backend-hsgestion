<?php

namespace App\Providers;

use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Articles\Infrastructure\Persistence\EloquentArticleRepository;
use App\Modules\Bank\Domain\Interfaces\BankRepositoryInterface;
use App\Modules\Bank\Infrastructure\Persistence\EloquentBankRepository;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Branch\Infrastructure\Persistence\EloquentBranchRepository;
use App\Modules\Brand\Domain\Interfaces\BrandRepositoryInterface;
use App\Modules\Brand\Infrastructure\Persistence\EloquentBrandRepository;
use App\Modules\Category\Domain\Interfaces\CategoryRepositoryInterface;
use App\Modules\Category\Infrastructure\Persistence\EloquentCategoryRepository;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\Company\Infrastructure\Persistence\EloquentCompanyRepository;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\CurrencyType\Infrastructure\Persistence\EloquentCurrencyTypeRepository;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\Customer\Infrastructure\Persistence\EloquentCustomerRepository;
use App\Modules\CustomerAddress\Domain\Interfaces\CustomerAddressRepositoryInterface;
use App\Modules\CustomerAddress\Infrastructure\Persistence\EloquentCustomerAddressRepository;
use App\Modules\CustomerDocumentType\Domain\Interfaces\CustomerDocumentTypeRepositoryInterface;
use App\Modules\CustomerDocumentType\Infrastructure\Persistence\EloquentCustomerDocumentTypeRepository;
use App\Modules\CustomerEmail\Domain\Interfaces\CustomerEmailRepositoryInterface;
use App\Modules\CustomerEmail\Infrastructure\Persistence\EloquentCustomerEmailRepository;
use App\Modules\CustomerPhone\Domain\Interfaces\CustomerPhoneRepositoryInterface;
use App\Modules\CustomerPhone\Infrastructure\Persistence\EloquentCustomerPhoneRepository;
use App\Modules\CustomerPortfolio\Domain\Interfaces\CustomerPortfolioRepositoryInterface;
use App\Modules\CustomerPortfolio\Infrastructure\Persistence\EloquentCustomerPortfolioRepository;
use App\Modules\CustomerType\Domain\Interfaces\CustomerTypeRepositoryInterface;
use App\Modules\CustomerType\Infrastructure\Persistence\EloquentCustomerTypeRepository;
use App\Modules\DigitalWallet\Domain\Interfaces\DigitalWalletRepositoryInterface;
use App\Modules\DigitalWallet\Infrastructure\Persistence\EloquentDigitalWalletRepository;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\DocumentType\Infrastructure\Persistence\EloquentDocumentTypeRepository;
use App\Modules\EmissionReason\Domain\Interfaces\EmissionReasonRepositoryInterface;
use App\Modules\EmissionReason\Infrastructure\Persistence\EloquentEmissionReasonRepository;
use App\Modules\ExchangeRate\Domain\Interfaces\ExchangeRateRepositoryInterface;
use App\Modules\ExchangeRate\Infrastructure\Persistence\EloquentExchangeRateRepository;
use App\Modules\IngressReason\Domain\Interfaces\IngressReasonRepositoryInterface;
use App\Modules\IngressReason\Infrastructure\Persistence\EloquentIngressReasonRepository;
use App\Modules\MeasurementUnit\Domain\Interfaces\MeasurementUnitRepositoryInterface;
use App\Modules\MeasurementUnit\Infrastructure\Persistence\EloquentMeasurementUnitRepository;
use App\Modules\Menu\Domain\Interfaces\MenuRepositoryInterface;
use App\Modules\Menu\Domain\Services\UserMenuService;
use App\Modules\Menu\Infrastructure\Persistence\EloquentMenuRepository;
use App\Modules\PaymentType\Domain\Interfaces\PaymentTypeRepositoryInterface;
use App\Modules\PercentageIGV\Domain\Interfaces\PercentageIGVRepositoryInterface;
use App\Modules\PercentageIGV\Infrastructure\Persistence\EloquentPercentageIGVRepository;
use App\Modules\RecordType\Domain\Interfaces\RecordTypeRepositoryInterface;
use App\Modules\RecordType\Infrastructure\Models\EloquentRecordType;
use App\Modules\SubCategory\Domain\Interfaces\SubCategoryRepositoryInterface;
use App\Modules\SubCategory\Infrastructure\Persistence\EloquentSubCategoryRepository;
use App\Modules\TransportCompany\Domain\Interfaces\TransportCompanyRepositoryInterface;
use App\Modules\TransportCompany\Infrastructure\Persistence\EloquentTransportCompanyRepository;
use App\Modules\Ubigeo\Departments\Domain\Interfaces\DepartmentRepositoryInterface;
use App\Modules\Ubigeo\Departments\Infrastructure\Persistence\EloquentDepartmentRepository;
use App\Modules\Ubigeo\Districts\Domain\Interfaces\DistrictRepositoryInterface;
use App\Modules\Ubigeo\Districts\Infrastructure\Persistence\EloquentDistrictRepository;
use App\Modules\Ubigeo\Provinces\Domain\Interfaces\ProvinceRepositoryInterface;
use App\Modules\Ubigeo\Provinces\Infrastructure\Persistence\EloquentProvinceRepository;
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
        $this->app->bind(CustomerTypeRepositoryInterface::class, EloquentCustomerTypeRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, EloquentCustomerRepository::class);
        $this->app->bind(CustomerDocumentTypeRepositoryInterface::class, EloquentCustomerDocumentTypeRepository::class);
            $this->app->bind(RecordTypeRepositoryInterface::class, EloquentBranchRepository::class);
        $this->app->bind(BranchRepositoryInterface::class, EloquentBranchRepository::class);
        $this->app->bind(CompanyRepositoryInterface::class, EloquentCompanyRepository::class);

        $this->app->bind(CustomerPhoneRepositoryInterface::class, EloquentCustomerPhoneRepository::class);
        $this->app->bind(CustomerEmailRepositoryInterface::class, EloquentCustomerEmailRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, EloquentDepartmentRepository::class);
        $this->app->bind(ProvinceRepositoryInterface::class, EloquentProvinceRepository::class);;
        $this->app->bind(ArticleRepositoryInterface::class, EloquentArticleRepository::class);;
        $this->app->bind(ProvinceRepositoryInterface::class, EloquentProvinceRepository::class);
        $this->app->bind(DistrictRepositoryInterface::class, EloquentDistrictRepository::class);
        $this->app->bind(CustomerAddressRepositoryInterface::class, EloquentCustomerAddressRepository::class);
        $this->app->bind(ExchangeRateRepositoryInterface::class, EloquentExchangeRateRepository::class);
        $this->app->bind(EmissionReasonRepositoryInterface::class, EloquentEmissionReasonRepository::class);
        $this->app->bind(IngressReasonRepositoryInterface::class, EloquentIngressReasonRepository::class);
        $this->app->bind(DocumentTypeRepositoryInterface::class, EloquentDocumentTypeRepository::class);
        $this->app->bind(BankRepositoryInterface::class, EloquentBankRepository::class);
        $this->app->bind(CurrencyTypeRepositoryInterface::class, EloquentCurrencyTypeRepository::class);
        $this->app->bind(DigitalWalletRepositoryInterface::class, EloquentDigitalWalletRepository::class);
        $this->app->bind(BrandRepositoryInterface::class, EloquentBrandRepository::class);
        $this->app->bind(CustomerPortfolioRepositoryInterface::class, EloquentCustomerPortfolioRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
