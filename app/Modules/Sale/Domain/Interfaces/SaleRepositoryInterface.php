<?php

namespace App\Modules\Sale\Domain\Interfaces;

use App\Modules\Sale\Domain\Entities\Sale;

interface SaleRepositoryInterface
{
    public function findAll(): array;
    public function save(Sale $sale): ?Sale;
    public function getLastDocumentNumber(): ?string;
    public function findById(int $id): ?Sale;
    public function update(Sale $sale): ?Sale;

    public function findByDocumentSale(int $documentTypeId, string $serie, string $correlative): ?Sale;
}
