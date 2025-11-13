<?php

namespace App\Modules\Purchases\Application\UseCases;

use App\Modules\Purchases\Application\DTOS\PurchaseDTO;
use App\Modules\Purchases\Domain\Entities\Purchase;
use App\Modules\Purchases\Domain\Interface\PurchaseRepositoryInterface;

class UpdatePurchaseUseCase{
    public function __construct(private readonly PurchaseRepositoryInterface $purchaseRepository)
    {
    }

    public function execute(PurchaseDTO $purchaseDTO ,int $id): ?Purchase
    {
       $updatePurchase = new Purchase(
            id: $id,
            company_id: $purchaseDTO->company_id,
            branch_id: $purchaseDTO->branch_id,
            supplier_id: $purchaseDTO->supplier_id,
            serie: $purchaseDTO->serie,
            correlative: $purchaseDTO->correlative,
            exchange_type: $purchaseDTO->exchange_type,
            methodpayment: $purchaseDTO->methodpayment,
            currency: $purchaseDTO->currency,
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