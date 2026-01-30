<?php

namespace App\Modules\CustomerAddress\Infrastructure\Controller;

use App\Http\Controllers\Controller;
use App\Modules\CustomerAddress\Application\UseCases\FindByIdCustomerAddressUseCase;
use App\Modules\CustomerAddress\Domain\Interfaces\CustomerAddressRepositoryInterface;
use App\Modules\CustomerAddress\Infrastructure\Resources\CustomerAddressResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class CustomerAddressController extends Controller
{
    public function __construct(private readonly CustomerAddressRepositoryInterface $customerAddressRepository){}

    public function indexByCustomerId(int $id)
    {
        $useCase = new FindByIdCustomerAddressUseCase($this->customerAddressRepository);
        $address = $useCase->execute($id);

        if (empty($address)) {
            return new JsonResponse(['message' => 'El cliente no tiene direcciones registradas'], 404);
        }

        return new JsonResponse(CustomerAddressResource::collection($address), 200);
    }
}