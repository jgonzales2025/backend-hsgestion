<?php

namespace App\Modules\Warranty\Application\UseCases;

use App\Modules\Warranty\Application\DTOs\UpdateWarrantyDTO;
use App\Modules\Warranty\Domain\Entities\UpdateWarranty;
use App\Modules\Warranty\Domain\Interfaces\WarrantyRepositoryInterface;

class UpdateWarrantyUseCase
{
    public function __construct(private readonly WarrantyRepositoryInterface $warrantyRepository){}

    public function execute(UpdateWarrantyDTO $updateWarrantyDTO, int $id): ?int
    {
        $updateWarranty = new UpdateWarranty(
            customer_email: $updateWarrantyDTO->customer_email,
            failure_description: $updateWarrantyDTO->failure_description,
            observations: $updateWarrantyDTO->observations,
            diagnosis: $updateWarrantyDTO->diagnosis,
            follow_up_diagnosis: $updateWarrantyDTO->follow_up_diagnosis,
            follow_up_status: $updateWarrantyDTO->follow_up_status,
            solution: $updateWarrantyDTO->solution,
            solution_date: $updateWarrantyDTO->solution_date,
            delivery_description: $updateWarrantyDTO->delivery_description,
            delivery_serie_art: $updateWarrantyDTO->delivery_serie_art,
            credit_note_serie: $updateWarrantyDTO->credit_note_serie,
            credit_note_correlative: $updateWarrantyDTO->credit_note_correlative,
            delivery_date: $updateWarrantyDTO->delivery_date,
            dispatch_note_serie: $updateWarrantyDTO->dispatch_note_serie,
            dispatch_note_correlative: $updateWarrantyDTO->dispatch_note_correlative,
            dispatch_note_date: $updateWarrantyDTO->dispatch_note_date
        );
        
        return $this->warrantyRepository->updateWarranty($updateWarranty, $id);
    }
}
