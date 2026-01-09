<?php

namespace App\Modules\SaleArticle\Application\UseCases;

use App\Modules\Articles\Application\UseCases\FindByIdArticleUseCase;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\SaleArticle\Application\DTOs\SaleArticleDTO;
use App\Modules\SaleArticle\Domain\Entities\SaleArticle;
use App\Modules\SaleArticle\Domain\Interfaces\SaleArticleRepositoryInterface;

readonly class CreateSaleArticleUseCase
{
    public function __construct(
        private readonly SaleArticleRepositoryInterface $saleArticleRepository,
        private readonly ArticleRepositoryInterface $articleRepository
    ){}

    public function execute(SaleArticleDTO $saleArticleDTO, float $subtotal_costo_neto): ?SaleArticle
    {
        $articleUseCase = new FindByIdArticleUseCase($this->articleRepository);
        $article = $articleUseCase->execute($saleArticleDTO->article_id);

        $saleArticle = new SaleArticle(
            id: 0,
            sale_id: $saleArticleDTO->sale_id,
            sku: null,
            article: $article,
            description: $saleArticleDTO->description,
            quantity: $saleArticleDTO->quantity,
            unit_price: $saleArticleDTO->unit_price,
            public_price: $saleArticleDTO->public_price,
            subtotal: $saleArticleDTO->subtotal,
            purchase_price: $saleArticleDTO->purchase_price,
            costo_neto: $saleArticleDTO->costo_neto,
            warranty: $saleArticleDTO->warranty
        );

        return $this->saleArticleRepository->save($saleArticle, $subtotal_costo_neto);
    }
}
