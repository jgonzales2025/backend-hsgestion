<?php

namespace App\Modules\Warranty\Application\UseCases;

use App\Modules\Warranty\Domain\Interfaces\WarrantyRepositoryInterface;

class UpdateStatusUseCase
{
    public function __construct(private WarrantyRepositoryInterface $warrantyRepository){}

    public function execute(int $id, int $status): void
    {
        $this->warrantyRepository->updateStatus($id, $status);
    }
}