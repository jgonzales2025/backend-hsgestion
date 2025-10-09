<?php

namespace App\Modules\PercentageIGV\Domain\Interfaces;

use App\Modules\PercentageIGV\Domain\Entities\PercentageIGV;

interface PercentageIGVRepositoryInterface
{
    public function findAll(): array;
    public function save(PercentageIGV $percentageIGV): ?PercentageIGV;
    public function findById(int $id): ?PercentageIGV;

    public function update(PercentageIGV $percentageIGV): ?PercentageIGV;
}
