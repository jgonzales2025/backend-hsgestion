<?php

namespace App\Modules\DetVoucherPurchase\application\UseCases;

use App\Modules\DetVoucherPurchase\application\DTOS\DetVoucherPurchaseDTO;
use App\Modules\DetVoucherPurchase\Domain\Entities\DetVoucherPurchase;
use App\Modules\DetVoucherPurchase\Domain\Interface\DetVoucherPurchaseRepositoryInterface;

class CreateDetVoucherPurchaseUseCase
{
    public function __construct(private readonly DetVoucherPurchaseRepositoryInterface $detVoucherPurchaseRepository) {}

    public function execute(DetVoucherPurchaseDTO $detVoucherPurchaseDTO)
    {
        $detVoucherPurchase = new DetVoucherPurchase(
            id: 0,
            voucher_id: $detVoucherPurchaseDTO->voucher_id,
            purchase_id: $detVoucherPurchaseDTO->purchase_id,
            amount: $detVoucherPurchaseDTO->amount
        );
       return  $this->detVoucherPurchaseRepository->create($detVoucherPurchase);
    }
}