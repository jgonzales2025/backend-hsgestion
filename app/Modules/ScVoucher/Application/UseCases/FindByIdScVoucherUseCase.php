<?php

namespace App\Modules\ScVoucher\Application\UseCases;

use App\Modules\ScVoucher\Domain\Entities\ScVoucher;
use App\Modules\ScVoucher\Domain\Interface\ScVoucherRepositoryInterface;

class FindByIdScVoucherUseCase
{
    public function __construct(
        private ScVoucherRepositoryInterface $scVoucherRepository,
    ) {}

    public function execute(int $id): ?ScVoucher
    {
        return $this->scVoucherRepository->findById($id);
    }
}
