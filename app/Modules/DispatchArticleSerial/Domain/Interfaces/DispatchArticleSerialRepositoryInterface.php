<?php

namespace App\Modules\DispatchArticleSerial\Domain\Interfaces;

use App\Modules\DispatchArticleSerial\Domain\Entities\DispatchArticleSerial;

interface DispatchArticleSerialRepositoryInterface
{
    public function save(DispatchArticleSerial $dispatchArticleSerial): ?DispatchArticleSerial;
    public function findAllTransferMovements(int $branchId): array;
    public function findSerialsByTransferOrderId(int $transferOrderId): array;
    public function updateStatusSerialEntry(int $branchId, string $serial): void;
    public function deleteByTransferOrderId(int $transferOrderId, array $serials): void;
}
