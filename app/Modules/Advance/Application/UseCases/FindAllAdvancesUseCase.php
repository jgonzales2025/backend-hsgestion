<?php

namespace App\Modules\Advance\Application\UseCases;

use App\Modules\Advance\Domain\Interfaces\AdvanceRepositoryInterface;

class FindAllAdvancesUseCase
{
    public function __construct(private AdvanceRepositoryInterface $advanceRepository)
    {
    }

    public function execute(?string $customer, int $company_id): ?array
    {
        return $this->advanceRepository->findAll($customer, $company_id);
    }
}