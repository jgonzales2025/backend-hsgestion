<?php

namespace App\Modules\Sale\Domain\Interfaces;

use App\Modules\Sale\Domain\Entities\Sale;
use App\Modules\Sale\Domain\Entities\SaleCreditNote;

interface SaleRepositoryInterface
{
    public function findAll(int $companyId): array;
    public function save(Sale $sale): ?Sale;
    public function saveCreditNote(SaleCreditNote $saleCreditNote): ?SaleCreditNote;
    public function getLastDocumentNumber(string $serie): ?string;
    public function findById(int $id): ?Sale;
    public function update(Sale $sale): ?Sale;
    public function findByDocumentSale(int $documentTypeId, string $serie, string $correlative): ?Sale;
    public function findAllProformas(): array;
    public function findSaleWithUpdatedQuantities(int $referenceDocumentTypeId, string $referenceSerie, string $referenceCorrelative): ?array;
    public function findAllCreditNotesByCustomerId(int $customerId): array;
}
