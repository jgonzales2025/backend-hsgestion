<?php

namespace App\Modules\Customer\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Customer\Application\DTOs\CustomerDTO;
use App\Modules\Customer\Application\UseCases\CreateCustomerUseCase;
use App\Modules\Customer\Application\UseCases\FindAllCustomersUseCase;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\Customer\Infrastructure\Persistence\EloquentCustomerRepository;
use App\Modules\Customer\Infrastructure\Requests\StoreCustomerRequest;
use App\Modules\Customer\Infrastructure\Resources\CustomerResource;
use App\Modules\CustomerPhone\Application\DTOs\CustomerPhoneDTO;
use App\Modules\CustomerPhone\Application\UseCases\CreateCustomerPhoneUseCase;
use App\Modules\CustomerPhone\Domain\Interfaces\CustomerPhoneRepositoryInterface;
use App\Modules\CustomerPhone\Infrastructure\Resources\CustomerPhoneResource;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{

    public function __construct(
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly CustomerPhoneRepositoryInterface $customerPhoneRepository
    ){}

    public function index(): array
    {
        $customersUseCase = new FindAllCustomersUseCase($this->customerRepository);
        $customers = $customersUseCase->execute();

        return CustomerResource::collection($customers)->resolve();
    }

    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $customerDTO = new CustomerDTO($validatedData);
        $customerUseCase = new CreateCustomerUseCase($this->customerRepository);
        $customer = $customerUseCase->execute($customerDTO);

        // Crear los teléfonos con foreach
        $createPhoneUseCase = new CreateCustomerPhoneUseCase($this->customerPhoneRepository);

        $phones = [];
        foreach ($validatedData['phones'] as $phoneData) {
            $customerPhoneDTO = new CustomerPhoneDTO([
                'phone' => $phoneData['phone'],
                'customer_id' => $customer->getId(),
                'status' => 1
            ]);
            $phones[] = $createPhoneUseCase->execute($customerPhoneDTO);
        }

        return response()->json([
            'customer' => (new CustomerResource($customer))->resolve(),
            'phones' => CustomerPhoneResource::collection($phones)->resolve(),
        ], 201);
    }
}
