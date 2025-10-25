<?php

namespace App\Modules\Collections\Domain\Interfaces;

use App\Modules\Collections\Domain\Entities\Collection;

interface CollectionRepositoryInterface
{
    public function findAll(): array;
    public function save(Collection $collection): ?Collection;
    public function findBySaleId(int $saleId): array;
    public function findById(int $id): ?Collection;
}
