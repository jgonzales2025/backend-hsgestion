<?php

namespace App\Modules\DispatchNotes\Domain\Interfaces;

use App\Modules\DispatchNotes\Domain\Entities\TransferOrder;

interface TransferOrderRepositoryInterface
{
    public function findAll(int $companyId, ?string $description, ?string $startDate, ?string $endDate, ?int $status, ?int $emissionReasonId);
    public function save(TransferOrder $transferOrder): TransferOrder;
    public function getLastDocumentNumber(string $serie): ?string;
    public function findById(int $id): ?TransferOrder;
    public function update(int $id, TransferOrder $transferOrder): void;
    public function updateStatusTransferOrder(int $transferOrderId): void;
}