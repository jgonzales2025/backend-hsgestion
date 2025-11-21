<?php

namespace App\Modules\EmissionReason\Domain\Interfaces;

use App\Modules\EmissionReason\Domain\Entities\EmissionReason;

interface EmissionReasonRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?EmissionReason;
    public function findAllForTransferOrders(): array;
}
