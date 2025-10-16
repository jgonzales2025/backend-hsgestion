<?php
namespace App\Modules\Articles\Application\UseCases;

use App\Modules\Articles\Application\DTOs\ArticleDTO;
use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Category\Application\UseCases\FindByIdCategoryUseCase;
use App\Modules\Category\Domain\Interfaces\CategoryRepositoryInterface;
use App\Modules\CurrencyType\Application\UseCases\FindAllCurrencyTypeUseCase;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use Illuminate\Support\Facades\Log;

readonly class CreateArticleUseCase
{
    // private ArticleRepositoryInterface $articleRepository;

    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
         private readonly ArticleRepositoryInterface $articleRepository,
        //  private readonly CurrencyTypeRepositoryInterface $currencyTypeRepository,
         )
    {}

    public function execute(ArticleDTO $articleDTO): Article
    {
         $categoryUseCase = new FindByIdCategoryUseCase( $this->categoryRepository);
         $categoryType = $categoryUseCase->execute($articleDTO->category_id);

        //     $currencyUseCase = new FindAllCurrencyTypeUseCase( $this->currencyTypeRepository);
        //  $currencyType = $currencyUseCase->execute($articleDTO->category_id);

        //     $categoryUseCase = new FindByIdCategoryUseCase(categoryRepository: $this->categoryRepository);
        //  $categoryType = $categoryUseCase->execute($articleDTO->category_id);

        //     $categoryUseCase = new FindByIdCategoryUseCase(categoryRepository: $this->categoryRepository);
        //  $categoryType = $categoryUseCase->execute($articleDTO->category_id);
            //   Log::info('categoryType',$categoryType->getId());

        $article = new Article(
            id: 0,
            cod_fab: $articleDTO->cod_fab,
            description: $articleDTO->description,
            short_description: $articleDTO->short_description,
            weight: $articleDTO->weight,
            with_deduction: $articleDTO->with_deduction,
            series_enabled: $articleDTO->series_enabled,
            // measurement_unit_id: $articleDTO->measurement_unit_id,
            // brand_id: $articleDTO->brand_id,
            // category_id: $articleDTO->category_id,
            location: $articleDTO->location,
            warranty: $articleDTO->warranty,
            tariff_rate: $articleDTO->tariff_rate,
            igv_applicable: $articleDTO->igv_applicable,
            plastic_bag_applicable: $articleDTO->plastic_bag_applicable,
            min_stock: $articleDTO->min_stock,
            currency_type_id: $articleDTO->currency_type_id,
            cost_to_price_percent: $articleDTO->cost_to_price_percent ?? 0,
            subcategory_id: $articleDTO->subcategory_id ?? 1,
            purchase_price: $articleDTO->purchase_price,
            public_price: $articleDTO->public_price,
            distributor_price: $articleDTO->distributor_price,
            authorized_price: $articleDTO->authorized_price,
            public_price_percent: $articleDTO->public_price_percent,
            distributor_price_percent: $articleDTO->distributor_price_percent,
            authorized_price_percent: $articleDTO->authorized_price_percent,
            status: $articleDTO->status,
                user_id: $articleDTO->user_id ?? 1,  // Asegúrate de que esto esté presente
 
            // user_id: $articleDTO->user_id,
            // Parámetros opcionales
            brand: null,
            category: $categoryType ,
            currencyType: null,
            measurementUnit: null,
            precioIGv: null, // Se calculará automáticamente en el constructor
            venta: $articleDTO->venta ?? false,
            subCategory: null
        );

        return $this->articleRepository->save($article);
    }
}
