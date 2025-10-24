<?php

namespace App\Modules\Collections\Domain\Interfaces;

use App\Modules\Collections\Domain\Entities\Collection;

interface CollectionRepositoryInterface
{
    public function findAll(): array;
    public function save(Collection $collection): ?Collection;
}
