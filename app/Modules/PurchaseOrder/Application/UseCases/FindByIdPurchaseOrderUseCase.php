<?php

namespace App\Modules\PurchaseOrder\Application\UseCases;

use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\PurchaseOrder\Domain\Entities\PurchaseOrder;
use App\Modules\PurchaseOrder\Domain\Interfaces\PurchaseOrderRepositoryInterface;

readonly class FindByIdPurchaseOrderUseCase
{
    public function __construct(
        private readonly PurchaseOrderRepositoryInterface $purchaseOrderRepository,
    ){}

    public function execute(int $id): ?PurchaseOrder
    {
        return $this->purchaseOrderRepository->findById($id);
    }
}
