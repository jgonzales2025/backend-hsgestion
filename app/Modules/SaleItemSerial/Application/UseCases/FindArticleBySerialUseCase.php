<?php

namespace App\Modules\SaleItemSerial\Application\UseCases;

use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\SaleItemSerial\Domain\Interfaces\SaleItemSerialRepositoryInterface;

class FindArticleBySerialUseCase
{
    public function __construct(
        private SaleItemSerialRepositoryInterface $saleItemSerialRepository
    ) {
    }

    public function execute(string $serial): ?Article
    {
        return $this->saleItemSerialRepository->findArticleBySerial($serial);
    }
}