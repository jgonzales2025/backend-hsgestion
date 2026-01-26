<?php

namespace App\Modules\PettyCashMotive\Domain\Interface;

use App\Modules\PettyCashMotive\Domain\Entities\PettyCashMotive;

interface PettyCashMotiveInterfaceRepository{
    public function save(PettyCashMotive $pettyCashMotive): ?PettyCashMotive;
    public function update(PettyCashMotive $pettyCashMotive): ?PettyCashMotive;
    public function findAll(?string $description, ?string $receipt_type, ?string $status);
    public function findByReceiptTypeInfinite(int $receipt_type_id, ?string $description);
    public function findById(int $id): ?PettyCashMotive;
    public function updateStatus(int $id, int $status): void;
}
