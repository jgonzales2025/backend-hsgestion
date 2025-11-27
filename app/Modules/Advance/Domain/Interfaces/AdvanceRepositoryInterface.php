<?php

namespace App\Modules\Advance\Domain\Interfaces;

use App\Modules\Advance\Domain\Entities\Advance;

interface AdvanceRepositoryInterface
{
    public function save(Advance $advance): void;
    public function getLastDocumentNumber(): ?string;
    public function findByCustomerId(int $customer_id): ?Advance;
}