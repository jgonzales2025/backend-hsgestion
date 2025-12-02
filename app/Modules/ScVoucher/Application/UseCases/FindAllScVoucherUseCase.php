<?php

namespace App\Modules\ScVoucher\Application\UseCases;

use App\Modules\ScVoucher\Domain\Interface\ScVoucherRepositoryInterface;

class FindAllScVoucherUseCase
{
    public function __construct(
        private ScVoucherRepositoryInterface $scVoucherRepository,
    ) {}

    public function execute()
    {
        return $this->scVoucherRepository->findAll();
    }
}
