<?php

namespace App\Modules\CustomerAddress\Application\UseCases;

use App\Modules\CustomerAddress\Domain\Interfaces\CustomerAddressRepositoryInterface;

readonly class FindByIdCustomerAddressUseCase
{
    public function __construct(private readonly CustomerAddressRepositoryInterface $customerAddressRepository){}

    public function execute($id): array
    {
        return $this->customerAddressRepository->findByCustomerId($id);
    }
}
