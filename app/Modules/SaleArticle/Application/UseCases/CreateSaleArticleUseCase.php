<?php

namespace App\Modules\SaleArticle\Application\UseCases;

use App\Modules\SaleArticle\Application\DTOs\SaleArticleDTO;
use App\Modules\SaleArticle\Domain\Entities\SaleArticle;
use App\Modules\SaleArticle\Domain\Interfaces\SaleArticleRepositoryInterface;

readonly class CreateSaleArticleUseCase
{
    public function __construct(private readonly SaleArticleRepositoryInterface $saleArticleRepository){}

    public function execute(SaleArticleDTO $saleArticleDTO): ?SaleArticle
    {
        $saleArticle = new SaleArticle(
            id: 0,
            sale_id: $saleArticleDTO->sale_id,
            article_id: $saleArticleDTO->article_id,
            description: $saleArticleDTO->description,
            quantity: $saleArticleDTO->quantity,
            unit_price: $saleArticleDTO->unit_price,
            public_price: $saleArticleDTO->public_price,
            subtotal: $saleArticleDTO->subtotal
        );

        return $this->saleArticleRepository->save($saleArticle);
    }
}
