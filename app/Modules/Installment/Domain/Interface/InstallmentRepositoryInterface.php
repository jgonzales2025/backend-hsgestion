<?php

namespace App\Modules\Installment\Domain\Interface;

use App\Modules\Installment\Domain\Entities\Installment;

interface InstallmentRepositoryInterface
{
    public function saveInstallment(Installment $installment): void;
    public function getInstallmentsBySaleId(int $saleId): ?array;
    public function delete(int $saleId): void;
}