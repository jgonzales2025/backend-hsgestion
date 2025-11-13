<?php

namespace App\Modules\Customer\Application\UseCases;

use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;

readonly class UpdateStatusUseCase
{

    public function __construct(private readonly CustomerRepositoryInterface $customerRepository){}

    public function execute(int $customerId, int $status): void
    {
        $this->customerRepository->updateStatus($customerId, $status);
    }
}
