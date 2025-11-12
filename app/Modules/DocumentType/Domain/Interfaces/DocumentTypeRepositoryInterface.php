<?php

namespace App\Modules\DocumentType\Domain\Interfaces;

use App\Modules\DocumentType\Domain\Entities\DocumentType;

interface DocumentTypeRepositoryInterface
{
    public function findAll(): array;

    public function findById($id): ?DocumentType;

    public function findAllForSales(): array;

    public function findAllForInvoices(): array;
}
