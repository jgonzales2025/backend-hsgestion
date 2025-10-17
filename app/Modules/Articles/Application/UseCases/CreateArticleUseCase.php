<?php
namespace App\Modules\Articles\Application\UseCases;

use App\Modules\Articles\Application\DTOs\ArticleDTO;
use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Brand\Application\UseCases\FindByIdBrandUseCase;
use App\Modules\Brand\Domain\Interfaces\BrandRepositoryInterface;
use App\Modules\Category\Application\UseCases\FindByIdCategoryUseCase;
use App\Modules\Category\Domain\Interfaces\CategoryRepositoryInterface;
use App\Modules\CurrencyType\Application\UseCases\FindAllCurrencyTypeUseCase;
use App\Modules\CurrencyType\Application\UseCases\FindByIdCurrencyTypeUseCase;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\MeasurementUnit\Application\UseCases\FindByIdMeasurementUnit;
use App\Modules\MeasurementUnit\Domain\Interfaces\MeasurementUnitRepositoryInterface;
use App\Modules\User\Application\UseCases\GetUserByIdUseCase;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use App\Modules\UserAssignment\Domain\Interfaces\UserAssignmentRepositoryInterface;
 
readonly class CreateArticleUseCase
{
    // private ArticleRepositoryInterface $articleRepository;

    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly ArticleRepositoryInterface $articleRepository,
        private readonly MeasurementUnitRepositoryInterface $measurementUnitRepository,
        private readonly BrandRepositoryInterface $brandRepository,
         private readonly UserRepositoryInterface $userRepository,
        
         private readonly CurrencyTypeRepositoryInterface $currencyTypeRepository,
    ) {
    }

    public function execute(ArticleDTO $articleDTO): Article
    {
        $categoryUseCase = new FindByIdCategoryUseCase($this->categoryRepository);
        $categoryType = $categoryUseCase->execute($articleDTO->category_id);

            $userUseCase = new GetUserByIdUseCase( $this->userRepository);
         $user = $userUseCase->execute($articleDTO->user_id);

            $measurementUseCase = new FindByIdMeasurementUnit( $this->measurementUnitRepository);
         $measurementUseCaseType = $measurementUseCase->execute($articleDTO->measurement_unit_id);

            $BrandUseCase = new FindByIdBrandUseCase( $this->brandRepository);
         $brand = $BrandUseCase->execute($articleDTO->brand_id);

                 $currencyType = new FindByIdCurrencyTypeUseCase( $this->currencyTypeRepository);
         $currencyType = $currencyType->execute($articleDTO->currency_type_id);
        //   Log::info('categoryType',$categoryType->getId());

        $article = new Article( 
            id:null,
            cod_fab: $articleDTO->cod_fab,
            description: $articleDTO->description,
            short_description: $articleDTO->short_description,
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
            precioIGv: null,
            user: $user,
            venta: $articleDTO->venta ?? false,

        );

        return $this->articleRepository->save($article);
    }
}
