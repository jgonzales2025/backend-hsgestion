<?php

namespace App\Modules\ScVoucherdet\application\UseCases;

use App\Modules\ScVoucherdet\Domain\Interface\ScVoucherdetRepositoryInterface;

class FindByIdScVoucherdetUseCase
{
    public function __construct(
        private ScVoucherdetRepositoryInterface $scVoucherdetRepository,
    ) {}
    public function execute(int $id)
    {
        return $this->scVoucherdetRepository->findById($id);
    }
}