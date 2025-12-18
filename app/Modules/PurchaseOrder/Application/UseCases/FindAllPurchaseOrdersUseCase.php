<?php

namespace App\Modules\PurchaseOrder\Application\UseCases;

use App\Modules\PurchaseOrder\Domain\Interfaces\PurchaseOrderRepositoryInterface;

readonly class FindAllPurchaseOrdersUseCase
{
    public function __construct(private readonly PurchaseOrderRepositoryInterface $purchaseOrderRepository){}

    public function execute(string $role, array $branches, int $companyId, ?string $description, ?int $status)
    {
        return $this->purchaseOrderRepository->findAll($role, $branches, $companyId, $description, $status);
    }
}
