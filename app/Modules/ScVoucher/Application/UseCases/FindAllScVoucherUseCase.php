<?php

namespace App\Modules\ScVoucher\Application\UseCases;

use App\Modules\ScVoucher\Domain\Interface\ScVoucherRepositoryInterface;

class FindAllScVoucherUseCase
{
    public function __construct(
        private ScVoucherRepositoryInterface $scVoucherRepository,
    ) {}

    public function execute(?string $search, ?int $status)
    {
        return $this->scVoucherRepository->findAll($search, $status);
    }
}
