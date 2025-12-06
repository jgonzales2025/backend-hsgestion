<?php

namespace App\Modules\DetVoucherPurchase\Domain\Interface;
 
use App\Modules\DetVoucherPurchase\Domain\Entities\DetVoucherPurchase;

interface DetVoucherPurchaseRepositoryInterface
{
    public function create(DetVoucherPurchase $detVoucherPurchaseDTO): DetVoucherPurchase;
    public function findById(int $id): DetVoucherPurchase;
    public function findAll(): array;
    public function findByIdVoucher(int $id): array;
}