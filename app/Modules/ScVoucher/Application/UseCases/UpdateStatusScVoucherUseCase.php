<?php

namespace App\Modules\ScVoucher\Application\UseCases;

use App\Modules\ScVoucher\Domain\Interface\ScVoucherRepositoryInterface;

class UpdateStatusScVoucherUseCase 
{
    public function __construct(
        private ScVoucherRepositoryInterface $scVoucherRepository,
    ) {}

    public function execute(int $id, int $status)
    {
        return $this->scVoucherRepository->updateStatus($id, $status);
    }
   
}