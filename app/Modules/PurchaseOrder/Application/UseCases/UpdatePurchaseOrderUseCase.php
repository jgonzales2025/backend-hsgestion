<?php

namespace App\Modules\PurchaseOrder\Application\UseCases;

use App\Modules\Customer\Application\UseCases\FindByIdCustomerUseCase;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\PurchaseOrder\Application\DTOs\PurchaseOrderDTO;
use App\Modules\PurchaseOrder\Domain\Entities\PurchaseOrder;
use App\Modules\PurchaseOrder\Domain\Interfaces\PurchaseOrderRepositoryInterface;

readonly class UpdatePurchaseOrderUseCase
{
    public function __construct(
        private readonly PurchaseOrderRepositoryInterface $purchaseOrderRepository,
        private readonly CustomerRepositoryInterface $customerRepository
    ){}

    public function execute(PurchaseOrderDTO $purchaseOrderDTO, int $id): ?PurchaseOrder
    {
        $supplierUseCase = new FindByIdCustomerUseCase($this->customerRepository);
        $supplier = $supplierUseCase->execute($purchaseOrderDTO->supplier_id);

        $purchaseOrder = new PurchaseOrder(
            id: $id,
            company_id: $purchaseOrderDTO->company_id,
            branch_id: $purchaseOrderDTO->branch_id,
            serie: $purchaseOrderDTO->serie,
            correlative: $purchaseOrderDTO->correlative,
            date: $purchaseOrderDTO->date,
            delivery_date: $purchaseOrderDTO->delivery_date,
            contact: $purchaseOrderDTO->contact,
            order_number_supplier: $purchaseOrderDTO->order_number_supplier,
            supplier: $supplier,
            status: $purchaseOrderDTO->status
        );

        return $this->purchaseOrderRepository->update($purchaseOrder);
    }
}
