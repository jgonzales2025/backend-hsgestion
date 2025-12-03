<?php

namespace App\Modules\DetEntryguidePurchaseOrder\application\UseCases;

use App\Modules\DetEntryguidePurchaseorder\application\DTOS\DetEntryguidePurchaseorderDTO;
use App\Modules\DetEntryguidePurchaseOrder\Domain\Entities\DetEntryguidePurchaseOrder;
use App\Modules\DetEntryguidePurchaseOrder\Domain\Interface\DetEntryguidePurchaseOrderRepositoryInterface;

class CreateDetEntryguidePurchaseOrderUseCase
{
    public function __construct(private readonly DetEntryguidePurchaseOrderRepositoryInterface $detEntryguidePurchaseOrderRepository) {}

    public function execute(DetEntryguidePurchaseorderDTO $detEntryguidePurchaseOrderDTO)
    {
        $detEntryguidePurchaseOrder = new DetEntryguidePurchaseOrder(
            id: 0,
            purchase_order_id: $detEntryguidePurchaseOrderDTO->purchase_order_id,
            entry_guide_id: $detEntryguidePurchaseOrderDTO->entry_guide_id
        );
       return  $this->detEntryguidePurchaseOrderRepository->create($detEntryguidePurchaseOrder);
    }
}