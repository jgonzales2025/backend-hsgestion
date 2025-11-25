<?php

namespace App\Modules\Purchases\Application\UseCases;

use App\Modules\Branch\Application\UseCases\FindByIdBranchUseCase;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\CurrencyType\Application\UseCases\FindByIdCurrencyTypeUseCase;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\Customer\Application\UseCases\FindByIdCustomerUseCase;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\PaymentMethod\Application\UseCases\FindByIdPaymentMethodUseCase;
use App\Modules\PaymentMethod\Domain\Interfaces\PaymentMethodRepositoryInterface;
use App\Modules\Purchases\Application\DTOS\PurchaseDTO;
use App\Modules\Purchases\Domain\Entities\Purchase;
use App\Modules\Purchases\Domain\Interface\PurchaseRepositoryInterface;

class UpdatePurchaseUseCase{
    public function __construct(
        private readonly PurchaseRepositoryInterface $purchaseRepository,
        private readonly PaymentMethodRepositoryInterface $paymentTypeRepository,
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly CurrencyTypeRepositoryInterface $currencyRepository,
    )
    {
    }

    public function execute(PurchaseDTO $purchaseDTO ,int $id): ?Purchase
    {
           $metodoPago =  new FindByIdPaymentMethodUseCase($this->paymentTypeRepository);
       $payment = $metodoPago->execute($purchaseDTO->methodpayment);

        $branch = new FindByIdBranchUseCase($this->branchRepository);
        $branch = $branch->execute($purchaseDTO->branch_id);

        $supplier = new FindByIdCustomerUseCase($this->customerRepository);
        $supplier = $supplier->execute($purchaseDTO->supplier_id);

        $currency = new FindByIdCurrencyTypeUseCase($this->currencyRepository);
        $currency = $currency->execute($purchaseDTO->currency);

       $updatePurchase = new Purchase(
            id: $id,
            branch: $branch,
            supplier: $supplier,
            serie: $purchaseDTO->serie,
            correlative: $purchaseDTO->correlative,
            exchange_type: $purchaseDTO->exchange_type,
            methodpaymentO: $payment,
            currency: $currency,
            date: $purchaseDTO->date,
            date_ven: $purchaseDTO->date_ven,
            days: $purchaseDTO->days,
            observation: $purchaseDTO->observation,
            detraccion: $purchaseDTO->detraccion,
            fech_detraccion: $purchaseDTO->fech_detraccion,
            amount_detraccion: $purchaseDTO->amount_detraccion,
            is_detracion: $purchaseDTO->is_detracion,
            subtotal: $purchaseDTO->subtotal,
            total_desc: $purchaseDTO->total_desc,
            inafecto: $purchaseDTO->inafecto,
            igv: $purchaseDTO->igv,
            total: $purchaseDTO->total
        );
        return $this->purchaseRepository->update($updatePurchase);
    }
}
