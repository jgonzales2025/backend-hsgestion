<?php

namespace App\Modules\Advance\Domain\Interfaces;

use App\Modules\Advance\Domain\Entities\Advance;
use App\Modules\Advance\Domain\Entities\UpdateAdvance;

interface AdvanceRepositoryInterface
{
    public function save(Advance $advance): void;
    public function getLastDocumentNumber(): ?string;
    public function findByCustomerId(int $customer_id): ?array;
    public function findAll(?string $description, int $company_id);
    public function findById(int $id): ?Advance;
    public function update(UpdateAdvance $advance): void;
    public function toInvalidateAdvance(int $id): void;
}