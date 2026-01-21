<?php

namespace App\Modules\WarrantyStatus\Domain\Interfaces;

use App\Modules\WarrantyStatus\Domain\Entities\WarrantyStatus;

interface WarrantyStatusRepositoryInterface
{
    public function findAll(?int $type): array;
    public function findById(int $id): WarrantyStatus;
}
