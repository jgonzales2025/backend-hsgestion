<?php

namespace App\Modules\Sale\Domain\Interfaces;

use App\Modules\Sale\Domain\Entities\Sale;
use App\Modules\Sale\Domain\Entities\SaleCreditNote;

interface SaleRepositoryInterface
{
    public function findAll(int $companyId, ?string $start_date, ?string $end_date, ?string $description, ?int $status, ?int $payment_status);
    public function save(Sale $sale): ?Sale;
    public function saveCreditNote(SaleCreditNote $saleCreditNote): ?SaleCreditNote;
    public function getLastDocumentNumber(string $serie): ?string;
    public function findById(int $id): ?Sale;
    public function update(Sale $sale): ?Sale;
    public function findByDocumentSale(int $documentTypeId, string $serie, string $correlative): ?Sale;
    public function findAllProformas(?string $start_date, ?string $end_date);
    public function findSaleWithUpdatedQuantities(int $referenceDocumentTypeId, string $referenceSerie, string $referenceCorrelative): ?array;
    public function findAllCreditNotesByCustomerId(int $customerId): array;
    public function findCreditNoteById(int $id): ?SaleCreditNote;
    public function updateCreditNote(SaleCreditNote $saleCreditNote): ?SaleCreditNote;
    public function findAllPendingSalesByCustomerId(int $customerId): ?array;
    public function findAllDocumentsByCustomerId(int $customerId, ?int $payment_status, ?int $user_sale_id, ?string $start_date, ?string $end_date, ?int $document_type_id);
}
