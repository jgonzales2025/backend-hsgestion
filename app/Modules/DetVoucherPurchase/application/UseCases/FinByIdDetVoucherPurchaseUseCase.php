<?php

namespace App\Modules\DetVoucherPurchase\application\UseCases;

use App\Modules\DetVoucherPurchase\Domain\Interface\DetVoucherPurchaseRepositoryInterface;

class FindAllDetVoucherPurchaseUseCase
{
    public function __construct(private readonly DetVoucherPurchaseRepositoryInterface $detVoucherPurchaseRepository) {}

    public function execute(int $id)
    {
        return $this->detVoucherPurchaseRepository->findById($id);
    }
}