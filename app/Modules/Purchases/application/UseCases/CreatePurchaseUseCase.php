<?php

namespace App\Modules\Purchases\Application\UseCases;

use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;
use App\Modules\PaymentMethod\Application\UseCases\FindByIdPaymentMethodUseCase;
use App\Modules\PaymentMethod\Domain\Interfaces\PaymentMethodRepositoryInterface;
use App\Modules\Purchases\Application\DTOS\PurchaseDTO;
use App\Modules\Purchases\Domain\Entities\Purchase;
use App\Modules\Purchases\Domain\Interface\PurchaseRepositoryInterface;

class CreatePurchaseUseCase
{
    public function __construct(
        private readonly PurchaseRepositoryInterface $purchaseRepository,
        private readonly PaymentMethodRepositoryInterface $paymentTypeRepository,

    ) {}

    public function execute(PurchaseDTO $purchaseDTO): ?Purchase
    {
        $lastDocumentNumber = $this->purchaseRepository->getLastDocumentNumber();

        if ($lastDocumentNumber === null) {
            $documentNumber = '00001';
        } else {
            $nextNumber = intval($lastDocumentNumber) + 1;
            $documentNumber = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        }
        $purchaseDTO->correlative = $documentNumber;

        $metodoPago =  new FindByIdPaymentMethodUseCase($this->paymentTypeRepository);
        $payment = $metodoPago->execute($purchaseDTO->methodpayment);


        $puchaseCreate = new Purchase(
            id: 0,
            branch_id: $purchaseDTO->branch_id,
            supplier_id: $purchaseDTO->supplier_id,
            serie: $purchaseDTO->serie,
            correlative: $purchaseDTO->correlative,
            exchange_type: $purchaseDTO->exchange_type,
            methodpaymentO: $payment,
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
        return $this->purchaseRepository->save($puchaseCreate);
    }
}
