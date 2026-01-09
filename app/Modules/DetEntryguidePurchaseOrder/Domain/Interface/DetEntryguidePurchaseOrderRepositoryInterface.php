<?php

namespace App\Modules\DetEntryguidePurchaseOrder\Domain\Interface;

use App\Modules\DetEntryguidePurchaseOrder\Domain\Entities\DetEntryguidePurchaseOrder;

interface DetEntryguidePurchaseOrderRepositoryInterface
{
    public function create(DetEntryguidePurchaseOrder $detEntryguidePurchaseOrder): DetEntryguidePurchaseOrder;
    public function update(DetEntryguidePurchaseOrder $detEntryguidePurchaseOrder): DetEntryguidePurchaseOrder;
    public function findById(int $id): DetEntryguidePurchaseOrder;
    public function findByIdEntryGuide(int $id): array;
    public function findAll(): array;
    public function deleteByEntryGuideId(int $id): void;
}
