<?php

namespace App\Modules\CustomerDocumentType\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;



use App\Modules\CustomerDocumentType\Application\UseCases\FindAllCustomerDocumentTypesForDriversUseCase;
use App\Modules\CustomerDocumentType\Application\UseCases\FindAllCustomerDocumentUseCase;
use App\Modules\CustomerDocumentType\Domain\Interfaces\CustomerDocumentTypeRepositoryInterface;
use App\Modules\CustomerDocumentType\Infrastructure\Persistence\EloquentCustomerDocumentTypeRepository;
use App\Modules\CustomerDocumentType\Infrastructure\Resources\CustomerDocumentTypeResource;

class CustomerDocumentTypeController extends Controller
{
    protected $customerDocumentTypeRepository;

    public function __construct(){
  $this->customerDocumentTypeRepository = new EloquentCustomerDocumentTypeRepository();
    
    }

    public function index(): array
    {

        $useCase = new FindAllCustomerDocumentUseCase($this->customerDocumentTypeRepository);
    $customerDocumentTypes = $useCase->execute();

    return CustomerDocumentTypeResource::collection($customerDocumentTypes)->resolve();
    }
    public function indexForDrivers(): array
    {
        $customerDocumentTypeUseCase = new FindAllCustomerDocumentTypesForDriversUseCase($this->customerDocumentTypeRepository);
        $customerDocumentTypes = $customerDocumentTypeUseCase->execute();

        return CustomerDocumentTypeResource::collection($customerDocumentTypes)->resolve();
    }
}
