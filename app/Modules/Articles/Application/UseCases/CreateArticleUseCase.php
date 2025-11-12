<?php
namespace App\Modules\Articles\Application\UseCases;

use App\Modules\Articles\Application\DTOs\ArticleDTO;
use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Articles\Domain\Interfaces\FileStoragePort;
use App\Modules\Brand\Application\UseCases\FindByIdBrandUseCase;
use App\Modules\Brand\Domain\Interfaces\BrandRepositoryInterface;
use App\Modules\Category\Application\UseCases\FindByIdCategoryUseCase;
use App\Modules\Category\Domain\Interfaces\CategoryRepositoryInterface;
use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\CurrencyType\Application\UseCases\FindByIdCurrencyTypeUseCase;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\MeasurementUnit\Application\UseCases\FindByIdMeasurementUnit;
use App\Modules\MeasurementUnit\Domain\Interfaces\MeasurementUnitRepositoryInterface;
use App\Modules\SubCategory\Application\UseCases\FindByIdSubCategoryUseCase;
use App\Modules\SubCategory\Domain\Interfaces\SubCategoryRepositoryInterface;
use App\Modules\User\Application\UseCases\GetUserByIdUseCase;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;

readonly class CreateArticleUseCase
{


    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly ArticleRepositoryInterface $articleRepository,
        private readonly MeasurementUnitRepositoryInterface $measurementUnitRepository,
        private readonly BrandRepositoryInterface $brandRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly CurrencyTypeRepositoryInterface $currencyTypeRepository,
        private readonly SubCategoryRepositoryInterface $subCategoryRepository,
        private readonly CompanyRepositoryInterface $companyRepository,

    ) {
    }

    public function execute(ArticleDTO $articleDTO): Article
    {
        // \Log::info('company_type_id',[$articleDTO->company_type_id]);
        // \Log::info('user_id',[$articleDTO->user_id]);
        if ($articleDTO->category_id != null) {
            $categoryUseCase = new FindByIdCategoryUseCase($this->categoryRepository);
            $categoryType = $categoryUseCase->execute($articleDTO->category_id);
        }else{
            $categoryType = null;
        }

        if ($articleDTO->user_id != null) {
            $userUseCase = new GetUserByIdUseCase($this->userRepository);
            $user = $userUseCase->execute($articleDTO->user_id);
        }else{
            $user = null;
        }
 
        if ($articleDTO->measurement_unit_id != null) {
            $measurementUseCase = new FindByIdMeasurementUnit($this->measurementUnitRepository);
            $measurementUseCaseType = $measurementUseCase->execute($articleDTO->measurement_unit_id);
        }else{
            $measurementUseCaseType = null;
        }

        if ($articleDTO->brand_id != null) {
            $BrandUseCase = new FindByIdBrandUseCase($this->brandRepository);
            $brand = $BrandUseCase->execute($articleDTO->brand_id);
        }else{
            $brand = null;
        }

        if ($articleDTO->currency_type_id != null) {
            $currencyTypeUseCase = new FindByIdCurrencyTypeUseCase($this->currencyTypeRepository);
            $currencyType = $currencyTypeUseCase->execute($articleDTO->currency_type_id);
        }else{
            $currencyType = null;
        }

        if ($articleDTO->sub_category_id != null) {
            $subCategoryUseCase = new FindByIdSubCategoryUseCase($this->subCategoryRepository);
            $subCategoryType = $subCategoryUseCase->execute($articleDTO->sub_category_id);
        }else{
            $subCategoryType = null;
        }
 
        if ($articleDTO->company_type_id != null) {
            $CompanyUseCase = new FindByIdCompanyUseCase($this->companyRepository);
            $companyType = $CompanyUseCase->execute($articleDTO->company_type_id);
        }else{
            $companyType = null;
        }

        $article = new Article(
            id: null,
            cod_fab: $articleDTO->cod_fab,
            description: $articleDTO->description,
            weight: $articleDTO->weight,
            with_deduction: $articleDTO->with_deduction,
            series_enabled: $articleDTO->series_enabled,
            location: $articleDTO->location,
            warranty: $articleDTO->warranty,
            tariff_rate: $articleDTO->tariff_rate,
            igv_applicable: $articleDTO->igv_applicable,
            plastic_bag_applicable: $articleDTO->plastic_bag_applicable,
            min_stock: $articleDTO->min_stock,
            purchase_price: $articleDTO->purchase_price,
            public_price: $articleDTO->public_price,
            distributor_price: $articleDTO->distributor_price,
            authorized_price: $articleDTO->authorized_price,
            public_price_percent: $articleDTO->public_price_percent,
            distributor_price_percent: $articleDTO->distributor_price_percent,
            authorized_price_percent: $articleDTO->authorized_price_percent,
            status: $articleDTO->status, 
            brand: $brand,
            category: $categoryType,
            currencyType: $currencyType,
            measurementUnit: $measurementUseCaseType,
            user: $user,
            venta: $articleDTO->venta ?? false,
            subCategory: $subCategoryType,
            company: $companyType,
            image_url: $articleDTO->image_url,
            state_modify_article: $articleDTO->state_modify_article,
            filtNameEsp: $articleDTO->filtNameEsp,
            statusEsp: $articleDTO->statusEsp,


        );

        return $this->articleRepository->save($article);
    }
}
