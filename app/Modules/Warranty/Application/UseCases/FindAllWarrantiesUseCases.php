<?php

namespace App\Modules\Warranty\Application\UseCases;

use App\Modules\Warranty\Domain\Interfaces\WarrantyRepositoryInterface;

class FindAllWarrantiesUseCases
{
    public function __construct(private WarrantyRepositoryInterface $warrantyRepositoryInterface){}

    public function execute(?string $description, ?string $startDate, ?string $endDate, ?int $warrantyStatusId)
    {
        return $this->warrantyRepositoryInterface->findAll($description, $startDate, $endDate, $warrantyStatusId);
    }
}