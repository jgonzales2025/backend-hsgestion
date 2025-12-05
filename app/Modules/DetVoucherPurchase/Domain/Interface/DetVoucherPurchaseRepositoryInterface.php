<?php

namespace App\Modules\DetVoucherPurchase\Domain\Interface;

use App\Modules\DetVoucherPurchase\application\DTOS\DetVoucherPurchaseDTO;
use App\Modules\DetVoucherPurchase\Domain\Entities\DetVoucherPurchase;

interface DetVoucherPurchaseRepositoryInterface
{
    public function create(DetVoucherPurchaseDTO $detVoucherPurchaseDTO): DetVoucherPurchase;
    public function findById(int $id): DetVoucherPurchase;
    public function findAll(): array;
}