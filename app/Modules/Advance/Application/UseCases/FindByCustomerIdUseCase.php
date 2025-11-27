<?php

namespace App\Modules\Advance\Application\UseCases;

use App\Modules\Advance\Domain\Entities\Advance;
use App\Modules\Advance\Domain\Interfaces\AdvanceRepositoryInterface;

class FindByCustomerIdUseCase
{
    public function __construct(private AdvanceRepositoryInterface $advanceRepository)
    {
    }

    public function execute(int $customer_id): ?Advance
    {
        return $this->advanceRepository->findByCustomerId($customer_id);
    }
}