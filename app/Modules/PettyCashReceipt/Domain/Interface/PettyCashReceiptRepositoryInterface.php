<?php

namespace App\Modules\PettyCashReceipt\Domain\Interface;

use App\Modules\PettyCashReceipt\Domain\Entities\PettyCashReceipt;

interface PettyCashReceiptRepositoryInterface
{
    public function findAll(?string $filter): array;
    public function findById(int $id): ?PettyCashReceipt;
    public function save(PettyCashReceipt $pettyCashReceipt): ?PettyCashReceipt;
    public function update(PettyCashReceipt $pettyCashReceipt): ?PettyCashReceipt;
    public function getLastDocumentNumber(string $serie): ?string;
}