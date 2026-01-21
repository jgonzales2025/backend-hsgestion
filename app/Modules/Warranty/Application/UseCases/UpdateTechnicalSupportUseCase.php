<?php

namespace App\Modules\Warranty\Application\UseCases;

use App\Modules\Warranty\Application\DTOs\UpdateTechnicalSupportDTO;
use App\Modules\Warranty\Domain\Entities\UpdateTechnicalSupport;
use App\Modules\Warranty\Domain\Interfaces\WarrantyRepositoryInterface;

class UpdateTechnicalSupportUseCase
{
    public function __construct(private readonly WarrantyRepositoryInterface $repository){}
    
    public function execute(UpdateTechnicalSupportDTO $dto, int $id): ?int
    {
        $updatedTechnicalSupport = new UpdateTechnicalSupport(
            customer_phone: $dto->customer_phone,
            customer_email: $dto->customer_email,
            failure_description: $dto->failure_description,
            observations: $dto->observations,
            diagnosis: $dto->diagnosis,
            contact: $dto->contact
        );
        
        return $this->repository->updateTechnicalSupport($updatedTechnicalSupport, $id);
    }
}
