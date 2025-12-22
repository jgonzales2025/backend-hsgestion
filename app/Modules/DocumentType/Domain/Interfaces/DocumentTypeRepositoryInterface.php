<?php

namespace App\Modules\DocumentType\Domain\Interfaces;

use App\Modules\DocumentType\Domain\Entities\DocumentType;

interface DocumentTypeRepositoryInterface
{
    public function findAll(): array;

    public function findById($id): ?DocumentType;

    public function findAllForSales(): array;

    public function findAllForInvoices(): array;

    public function findAllForPettyCash(): array;
    public function findAllForPettyCashInfinite();

    public function findAllForDocumentSales(): array;
    
    public function findAllForPurchases(): array;

    public function findAllForEntryGuides(): array;
}
