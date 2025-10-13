<?php

namespace App\Modules\CustomerPhone\Application\UseCases;

use App\Modules\CustomerPhone\Domain\Interfaces\CustomerPhoneRepositoryInterface;

readonly class FindByCustomerIdPhoneUseCase
{
    public function __construct(private readonly CustomerPhoneRepositoryInterface $customerPhoneRepository){}

    public function execute(int $customerId): array
    {
        return $this->customerPhoneRepository->findByCustomerId($customerId);
    }
}
