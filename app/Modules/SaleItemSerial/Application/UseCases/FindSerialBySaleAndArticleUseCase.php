<?php

namespace App\Modules\SaleItemSerial\Application\UseCases;

use App\Modules\SaleItemSerial\Domain\Entities\SaleItemSerial;
use App\Modules\SaleItemSerial\Domain\Interfaces\SaleItemSerialRepositoryInterface;

class FindSerialBySaleAndArticleUseCase
{
    public function __construct(private readonly SaleItemSerialRepositoryInterface $saleItemSerialRepository){}

    public function execute(int $saleId, int $articleId): ?array
    {
        return $this->saleItemSerialRepository->findSerialBySaleAndArticle($saleId, $articleId);
    }
}
