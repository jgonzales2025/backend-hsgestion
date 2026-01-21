<?php

namespace App\Modules\WarrantyStatus\Application\UseCases;

use App\Modules\WarrantyStatus\Domain\Interfaces\WarrantyStatusRepositoryInterface;

class FindAllWarrantyStatusesUseCases
{
    public function __construct(private readonly WarrantyStatusRepositoryInterface $warrantyStatusRepository){}

    public function execute(?int $type) : array
    {
        return $this->warrantyStatusRepository->findAll($type);
    }
}
