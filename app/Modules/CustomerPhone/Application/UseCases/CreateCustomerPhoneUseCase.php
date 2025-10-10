<?php

namespace App\Modules\CustomerPhone\Application\UseCases;

use App\Modules\CustomerPhone\Application\DTOs\CustomerPhoneDTO;
use App\Modules\CustomerPhone\Domain\Entities\CustomerPhone;
use App\Modules\CustomerPhone\Domain\Interfaces\CustomerPhoneRepositoryInterface;

readonly class CreateCustomerPhoneUseCase
{
    public function __construct(private readonly CustomerPhoneRepositoryInterface $customerPhoneRepository) {}

    public function execute(CustomerPhoneDTO $customerPhoneDTO): CustomerPhone
    {
        $customerPhone = new CustomerPhone(
            id: 0,
            phone: $customerPhoneDTO->phone,
            customer_id: $customerPhoneDTO->customer_id,
            status: $customerPhoneDTO->status,
        );

        return $this->customerPhoneRepository->save($customerPhone);
    }
}
