<?php

namespace App\Modules\SaleArticle\Application\UseCases;

use App\Modules\SaleArticle\Domain\Interfaces\SaleArticleRepositoryInterface;

readonly class FindBySaleIdUseCase
{
    public function __construct(private readonly SaleArticleRepositoryInterface $saleArticleRepository){}

    public function execute($id): array
    {
        return $this->saleArticleRepository->findBySaleId($id);
    }
}
