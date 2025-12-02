<?php

namespace App\Modules\ScVoucherdet\application\UseCases;

use App\Modules\ScVoucherdet\Domain\Interface\ScVoucherdetRepositoryInterface;

class FindAllScVoucherdetUseCase
{
    public function __construct(
        private ScVoucherdetRepositoryInterface $scVoucherdetRepository,
    ) {}
    public function execute()
    {
        return $this->scVoucherdetRepository->findAll();
    }
}