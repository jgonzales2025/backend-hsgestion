<?php

namespace App\Modules\PurchaseOrder\Application\UseCases;

use App\Modules\PurchaseOrder\Domain\Interfaces\PurchaseOrderRepositoryInterface;

readonly class GetLastDocumentNumberUserCase
{
    public function __construct(private readonly PurchaseOrderRepositoryInterface $purchaseOrderRepository){}

    public function execute(string $serie): ?string
    {
        return $this->purchaseOrderRepository->getLastDocumentNumber($serie);
    }
}
