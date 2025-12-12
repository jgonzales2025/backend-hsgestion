<?php

namespace App\Modules\Brand\Domain\Interfaces;

use App\Modules\Brand\Domain\Entities\Brand;

interface BrandRepositoryInterface
{
    public function save(Brand $brand): ?Brand;
    public function findAll(?string $name, ?int $status);
    public function findAllPaginateInfinite(?string $name);
    public function findById(int $id): ?Brand;
    public function update(Brand $brand): ?Brand;
    public function updateStatus(int $brandId, int $status): void;
}
