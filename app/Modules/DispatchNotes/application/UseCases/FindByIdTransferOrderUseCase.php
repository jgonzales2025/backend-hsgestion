<?php

namespace App\Modules\DispatchNotes\Application\UseCases;

use App\Modules\DispatchNotes\Domain\Entities\TransferOrder;
use App\Modules\DispatchNotes\Domain\Interfaces\TransferOrderRepositoryInterface;

class FindByIdTransferOrderUseCase 
{
    public function __construct(private TransferOrderRepositoryInterface $transferOrderRepository){}

    public function execute(int $id): ?TransferOrder
    {
        return $this->transferOrderRepository->findById($id);
    }
}