<?php

namespace App\Modules\MonthlyClosure\Domain\Interfaces;

use App\Modules\MonthlyClosure\Domain\Entities\MonthlyClosure;

interface MonthlyClosureRepositoryInterface
{
    public function findAll(): ?array;
    public function save(MonthlyClosure $monthlyClosure): ?MonthlyClosure;
    public function findById(int $id): ?MonthlyClosure;
    public function updateStSales(int $id, int $status): void;
}
