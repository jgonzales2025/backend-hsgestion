<?php

namespace App\Modules\PurchaseOrder\Application\UseCases;

use App\Modules\Branch\Application\UseCases\FindByIdBranchUseCase;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\CurrencyType\Application\UseCases\FindByIdCurrencyTypeUseCase;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\Customer\Application\UseCases\FindByIdCustomerUseCase;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\PaymentType\Application\UseCases\FindByIdPaymentTypeUseCase;
use App\Modules\PaymentType\Domain\Interfaces\PaymentTypeRepositoryInterface;
use App\Modules\PurchaseOrder\Application\DTOs\PurchaseOrderDTO;
use App\Modules\PurchaseOrder\Domain\Entities\PurchaseOrder;
use App\Modules\PurchaseOrder\Domain\Interfaces\PurchaseOrderRepositoryInterface;

readonly class UpdatePurchaseOrderUseCase
{
    public function __construct(
        private readonly PurchaseOrderRepositoryInterface $purchaseOrderRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly CurrencyTypeRepositoryInterface $currencyTypeRepository,
        private readonly PaymentTypeRepositoryInterface $paymentTypeRepository
    ){}

    public function execute(PurchaseOrderDTO $purchaseOrderDTO, int $id): ?PurchaseOrder
    {
        $supplierUseCase = new FindByIdCustomerUseCase($this->customerRepository);
        $supplier = $supplierUseCase->execute($purchaseOrderDTO->supplier_id);

        $branchUseCase = new FindByIdBranchUseCase($this->branchRepository);
        $branch = $branchUseCase->execute($purchaseOrderDTO->branch_id);

        $currencyTypeUseCase = new FindByIdCurrencyTypeUseCase($this->currencyTypeRepository);
        $currencyType = $currencyTypeUseCase->execute($purchaseOrderDTO->currency_type_id);

        $paymentTypeUseCase = new FindByIdPaymentTypeUseCase($this->paymentTypeRepository);
        $paymentType = $paymentTypeUseCase->execute($purchaseOrderDTO->payment_type_id);

        $purchaseOrder = new PurchaseOrder(
            id: $id,
            company_id: $purchaseOrderDTO->company_id,
            branch: $branch,
            serie: $purchaseOrderDTO->serie,
            correlative: $purchaseOrderDTO->correlative,
            date: $purchaseOrderDTO->date,
            delivery_date: $purchaseOrderDTO->delivery_date,
            due_date: $purchaseOrderDTO->due_date,
            days: $purchaseOrderDTO->days,
            contact_name: $purchaseOrderDTO->contact_name,
            contact_phone: $purchaseOrderDTO->contact_phone,
            currencyType: $currencyType,
            parallel_rate: $purchaseOrderDTO->parallel_rate,
            paymentType: $paymentType,
            order_number_supplier: $purchaseOrderDTO->order_number_supplier,
            observations: $purchaseOrderDTO->observations,
            supplier: $supplier,
            status: $purchaseOrderDTO->status,
            subtotal: $purchaseOrderDTO->subtotal,
            igv: $purchaseOrderDTO->igv,
            total: $purchaseOrderDTO->total
        );

        return $this->purchaseOrderRepository->update($purchaseOrder);
    }
}
