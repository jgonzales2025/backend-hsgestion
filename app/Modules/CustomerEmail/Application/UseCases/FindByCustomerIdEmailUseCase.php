<?php

namespace App\Modules\CustomerEmail\Application\UseCases;

use App\Modules\CustomerEmail\Domain\Interfaces\CustomerEmailRepositoryInterface;

readonly class FindByCustomerIdEmailUseCase
{
    public function __construct(private readonly CustomerEmailRepositoryInterface $customerEmailRepository){}

    public function execute(int $customerId): array
    {
        return $this->customerEmailRepository->findByCustomerId($customerId);
    }
}
