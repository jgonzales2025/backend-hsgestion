<?php

namespace App\Modules\PurchaseItemSerials\Application\UseCases;

use App\Modules\PurchaseItemSerials\Domain\Entities\PurchaseItemSerial;
use App\Modules\PurchaseItemSerials\Domain\Interface\EntryItemSerialRepositoryInterface;

class FindByIdPurchaseItemSerialUseCase
{
    public function __construct(
        private readonly EntryItemSerialRepositoryInterface $purchaseItemSerialRepositoryInterface
    ) {
    }

    public function execute(int $id): array
    {
        return $this->purchaseItemSerialRepositoryInterface->findById($id);
    }


}
