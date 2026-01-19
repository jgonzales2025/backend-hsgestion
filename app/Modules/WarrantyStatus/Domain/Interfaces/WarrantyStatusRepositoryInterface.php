<?php

namespace App\Modules\WarrantyStatus\Domain\Interfaces;

use App\Modules\WarrantyStatus\Domain\Entities\WarrantyStatus;

interface WarrantyStatusRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): WarrantyStatus;
}
