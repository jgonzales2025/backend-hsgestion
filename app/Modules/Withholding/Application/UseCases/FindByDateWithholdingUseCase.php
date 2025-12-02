<?php

namespace App\Modules\Withholding\Application\UseCases;

use App\Modules\Withholding\Domain\Entities\Withholding;
use App\Modules\Withholding\Domain\Interface\WithholdingRepositoryInterface;

class FindByDateWithholdingUseCase
{
    public function __construct(
        private WithholdingRepositoryInterface $withholdingRepository
    ) {}

    public function execute(string $date): ?Withholding
    {
        return $this->withholdingRepository->findByDate($date);
    }
}