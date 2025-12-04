<?php

namespace App\Modules\Bank\Domain\Interfaces;

use App\Modules\Bank\Domain\Entities\Bank;

interface BankRepositoryInterface
{
    public function findAll(?string $description, ?int $status, ?int $currency_type_id);
    public function save(Bank $bank): ?Bank;
    public function findById(int $id): ?Bank;
    public function update(Bank $bank): ?Bank;
    public function updateStatus(int $bankId, int $status): void;
}
