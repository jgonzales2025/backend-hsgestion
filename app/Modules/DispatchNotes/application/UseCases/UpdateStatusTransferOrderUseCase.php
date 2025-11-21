<?php

namespace App\Modules\DispatchNotes\Application\UseCases;

use App\Modules\DispatchNotes\Domain\Interfaces\TransferOrderRepositoryInterface;

class UpdateStatusTransferOrderUseCase
{
    public function __construct(
        private TransferOrderRepositoryInterface $transferOrderRepository
    ) {
    }

    public function execute(int $transferOrderId): void
    {
        $this->transferOrderRepository->updateStatusTransferOrder($transferOrderId);
    }
}