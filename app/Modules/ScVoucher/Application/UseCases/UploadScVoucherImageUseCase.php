<?php

namespace App\Modules\ScVoucher\Application\UseCases;

use App\Modules\ScVoucher\Domain\Interface\ScVoucherRepositoryInterface;

class UploadScVoucherImageUseCase
{
    public function __construct(
        private ScVoucherRepositoryInterface $scVoucherRepository
    ) {}

    public function execute(int $id, string $path): void
    {
        $this->scVoucherRepository->updateImagePath($id, $path);
    }
}
