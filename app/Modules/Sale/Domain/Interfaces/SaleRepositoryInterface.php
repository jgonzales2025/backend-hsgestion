<?php

namespace App\Modules\Sale\Domain\Interfaces;

use App\Modules\Sale\Domain\Entities\Sale;

interface SaleRepositoryInterface
{
    public function findAll(): array;
    public function save(Sale $sale): ?Sale;
    public function getLastDocumentNumber(): ?string;
}
