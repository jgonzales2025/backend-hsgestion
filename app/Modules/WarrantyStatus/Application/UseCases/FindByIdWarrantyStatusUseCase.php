<?php

namespace App\Modules\WarrantyStatus\Application\UseCases;

use App\Modules\WarrantyStatus\Domain\Entities\WarrantyStatus;
use App\Modules\WarrantyStatus\Domain\Interfaces\WarrantyStatusRepositoryInterface;

class FindByIdWarrantyStatusUseCase
{
    public function __construct(private readonly WarrantyStatusRepositoryInterface $warrantyStatusRepository){}
    public function execute(int $id): WarrantyStatus
    {
        return $this->warrantyStatusRepository->findById($id);
    }
}