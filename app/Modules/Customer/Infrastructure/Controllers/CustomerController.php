<?php

namespace App\Modules\Customer\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Customer\Application\DTOs\CustomerDTO;
use App\Modules\Customer\Application\UseCases\CreateCustomerUseCase;
use App\Modules\Customer\Application\UseCases\FindAllCustomersUseCase;
use App\Modules\Customer\Infrastructure\Persistence\EloquentCustomerRepository;
use App\Modules\Customer\Infrastructure\Requests\StoreCustomerRequest;
use App\Modules\Customer\Infrastructure\Resources\CustomerResource;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{

    public function __construct(private readonly EloquentCustomerRepository $customerRepository){}

    public function index(): array
    {
        $customersUseCase = new FindAllCustomersUseCase($this->customerRepository);
        $customers = $customersUseCase->execute();

        return CustomerResource::collection($customers)->resolve();
    }

    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $customerDTO = new CustomerDTO($request->validated());
        $customerUseCase = new CreateCustomerUseCase($this->customerRepository);
        $customer = $customerUseCase->execute($customerDTO);

        return response()->json((new CustomerResource($customer))->resolve(),201);
    }
}
