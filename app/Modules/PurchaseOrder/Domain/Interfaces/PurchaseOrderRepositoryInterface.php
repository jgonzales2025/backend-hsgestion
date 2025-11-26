<?php

namespace App\Modules\PurchaseOrder\Domain\Interfaces;

use App\Modules\PurchaseOrder\Domain\Entities\PurchaseOrder;

interface PurchaseOrderRepositoryInterface
{
    public function findAll(string $role, array $branches, int $companyId): array;
    public function save(PurchaseOrder $purchaseOrder): ?PurchaseOrder;
    public function getLastDocumentNumber(string $serie): ?string;
    public function findById(int $id): ?PurchaseOrder;
    public function update(PurchaseOrder $purchaseOrder): ?PurchaseOrder;
    public function findByIds(array $ids): array;
    public function allBelongToSameCustomer(array $ids): bool;
   
}
