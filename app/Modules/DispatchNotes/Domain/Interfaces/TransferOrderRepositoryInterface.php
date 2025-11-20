<?php

namespace App\Modules\DispatchNotes\Domain\Interfaces;

use App\Modules\DispatchNotes\Domain\Entities\TransferOrder;

interface TransferOrderRepositoryInterface
{
    public function findAll(int $companyId): array;
    public function save(TransferOrder $transferOrder): TransferOrder;
    public function getLastDocumentNumber(string $serie): ?string;
    public function findById(int $id): ?TransferOrder;
    public function updateSerialStatus(int $transferOrderId, string $serial): void;
}