<?php

namespace App\Modules\ScVoucher\Application\UseCases;

use App\Modules\ScVoucher\Domain\Interface\ScVoucherRepositoryInterface;

class FindAllScVoucherUseCase
{
    public function __construct(
        private ScVoucherRepositoryInterface $scVoucherRepository,
    ) {}

    public function execute(?string $search, ?int $status, ?string $fecha_inicio, ?string $fecha_fin)
    {
        return $this->scVoucherRepository->findAll($search, $status, $fecha_inicio, $fecha_fin);
    }
}
