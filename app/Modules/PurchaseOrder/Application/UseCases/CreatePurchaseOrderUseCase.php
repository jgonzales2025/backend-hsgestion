<?php

namespace App\Modules\PurchaseOrder\Application\UseCases;

use App\Modules\Customer\Application\UseCases\FindByIdCustomerUseCase;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\PurchaseOrder\Application\DTOs\PurchaseOrderDTO;
use App\Modules\PurchaseOrder\Domain\Entities\PurchaseOrder;
use App\Modules\PurchaseOrder\Domain\Interfaces\PurchaseOrderRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;

readonly class CreatePurchaseOrderUseCase
{
    public function __construct(
        private readonly PurchaseOrderRepositoryInterface $purchaseOrderRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly DocumentNumberGeneratorService $documentNumberGenerator
    ){}

    public function execute(PurchaseOrderDTO $purchaseOrderDTO): ?PurchaseOrder
    {
        $supplierUseCase = new FindByIdCustomerUseCase($this->customerRepository);
        $supplier = $supplierUseCase->execute($purchaseOrderDTO->supplier_id);

        $lastDocumentNumber = $this->purchaseOrderRepository->getLastDocumentNumber($purchaseOrderDTO->serie);
        $documentNumber = $this->documentNumberGenerator->generateNextNumber($lastDocumentNumber);

        $purchaseOrder = new PurchaseOrder(
            id: 0,
            company_id: $purchaseOrderDTO->company_id,
            branch_id: $purchaseOrderDTO->branch_id,
            serie: $purchaseOrderDTO->serie,
            correlative: $documentNumber,
            date: $purchaseOrderDTO->date,
            delivery_date: $purchaseOrderDTO->delivery_date,
            contact: $purchaseOrderDTO->contact,
            order_number_supplier: $purchaseOrderDTO->order_number_supplier,
            supplier: $supplier,
            status: null
        );

        return $this->purchaseOrderRepository->save($purchaseOrder);
    }
}
