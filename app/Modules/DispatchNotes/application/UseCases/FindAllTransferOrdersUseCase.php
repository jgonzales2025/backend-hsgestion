<?php

namespace App\Modules\DispatchNotes\application\UseCases;

use App\Modules\DispatchNotes\Domain\Interfaces\TransferOrderRepositoryInterface;

class FindAllTransferOrdersUseCase
{

    public function __construct(private TransferOrderRepositoryInterface $transferOrderRepository){}

    public function execute(int $companyId): array
    {
        return $this->transferOrderRepository->findAll($companyId);
    }
}