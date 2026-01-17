<?php

namespace App\Modules\Warranty\Application\UseCases;

use App\Modules\Warranty\Domain\Entities\Warranty;
use App\Modules\Warranty\Domain\Interfaces\WarrantyRepositoryInterface;

class FindByIdWarrantyUseCase
{
    public function __construct(private readonly WarrantyRepositoryInterface $warrantyRepository)
    {
    }

    public function execute(int $id)
    {
        return $this->warrantyRepository->findById($id);
    }
}