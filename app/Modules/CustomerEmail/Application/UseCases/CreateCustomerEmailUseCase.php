<?php

namespace App\Modules\CustomerEmail\Application\UseCases;

use App\Modules\CustomerEmail\Application\DTOs\CustomerEmailDTO;
use App\Modules\CustomerEmail\Domain\Entities\CustomerEmail;
use App\Modules\CustomerEmail\Domain\Interfaces\CustomerEmailRepositoryInterface;

readonly class CreateCustomerEmailUseCase
{
    public function __construct(private readonly CustomerEmailRepositoryInterface $customerEmailRepository){}

    public function execute(CustomerEmailDTO $customerEmailDTO): CustomerEmail
    {
        $customerEmail = new CustomerEmail(
            id: 0,
            email: $customerEmailDTO->email,
            customer_id: $customerEmailDTO->customer_id,
            status: $customerEmailDTO->status,
        );

        return $this->customerEmailRepository->save($customerEmail);
    }
}
