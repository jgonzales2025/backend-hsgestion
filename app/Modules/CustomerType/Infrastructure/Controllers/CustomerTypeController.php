<?php

namespace App\Modules\CustomerType\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CustomerType\Application\UseCases\FindAllCustomerTypeUseCase;
use App\Modules\CustomerType\Domain\Interfaces\CustomerTypeRepositoryInterface;
use App\Modules\CustomerType\Infrastructure\Resources\CustomerTypeResource;

class CustomerTypeController extends Controller
{
    protected $customerTypeRepository;

    public function __construct(CustomerTypeRepositoryInterface $customerTypeRepository)
    {
        $this->customerTypeRepository = $customerTypeRepository;
    }

    public function index(): array
    {
        $customerTypesUseCase = new FindAllCustomerTypeUseCase($this->customerTypeRepository);
        $customerTypes = $customerTypesUseCase->execute();

        return CustomerTypeResource::collection($customerTypes)->resolve();
    }
}
