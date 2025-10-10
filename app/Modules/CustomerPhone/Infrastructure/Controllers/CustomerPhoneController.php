<?php

namespace App\Modules\CustomerPhone\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CustomerPhone\Application\UseCases\FindAllCustomerPhonesUseCase;
use App\Modules\CustomerPhone\Domain\Interfaces\CustomerPhoneRepositoryInterface;
use App\Modules\CustomerPhone\Infrastructure\Resources\CustomerPhoneResource;

class CustomerPhoneController extends Controller
{
    public function __construct(private readonly CustomerPhoneRepositoryInterface $customerPhoneRepository){}

    public function index(): array
    {
        $customerPhoneUseCase = new FindAllCustomerPhonesUseCase($this->customerPhoneRepository);
        $customerPhones = $customerPhoneUseCase->execute();

        return CustomerPhoneResource::collection($customerPhones)->resolve();
    }
}
