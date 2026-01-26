<?php

namespace App\Modules\DispatchNotes\application\UseCases;

use App\Modules\DispatchNotes\Domain\Interfaces\TransferOrderRepositoryInterface;

class ToInvalidateTransferOrderUseCase
{
    public function __construct(private readonly TransferOrderRepositoryInterface $transferOrderRepository){}

    public function execute(int $id): void 
    {
        $this->transferOrderRepository->toInvalidate($id);
    }
}