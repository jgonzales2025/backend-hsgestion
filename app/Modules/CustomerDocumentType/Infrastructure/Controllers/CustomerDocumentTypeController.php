<?php

namespace App\Modules\CustomerDocumentType\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CustomerDocumentType\Application\UseCases\FindAllCustomerDocumentTypesForDriversUseCase;
use App\Modules\CustomerDocumentType\Domain\Interfaces\CustomerDocumentTypeRepositoryInterface;
use App\Modules\CustomerDocumentType\Infrastructure\Resources\CustomerDocumentTypeResource;

class CustomerDocumentTypeController extends Controller
{
    public function __construct(private readonly CustomerDocumentTypeRepositoryInterface $customerDocumentTypeRepository){}

    public function indexForDrivers(): array
    {
        $customerDocumentTypeUseCase = new FindAllCustomerDocumentTypesForDriversUseCase($this->customerDocumentTypeRepository);
        $customerDocumentTypes = $customerDocumentTypeUseCase->execute();

        return CustomerDocumentTypeResource::collection($customerDocumentTypes)->resolve();
    }
}
