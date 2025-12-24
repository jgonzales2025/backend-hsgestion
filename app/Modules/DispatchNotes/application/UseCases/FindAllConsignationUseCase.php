<?php

namespace App\Modules\DispatchNotes\Application\UseCases;

use App\Modules\DispatchNotes\Domain\Interfaces\TransferOrderRepositoryInterface;

class FindAllConsignationUseCase
{
    public function __construct(private TransferOrderRepositoryInterface $transferOrderRepository)
    {
    }

    public function execute(int $companyId, ?string $description, ?string $startDate, ?string $endDate, ?int $status, ?int $emissionReasonId)
    {
        return $this->transferOrderRepository->findAllConsignations($companyId, $description, $startDate, $endDate, $status, $emissionReasonId);
    }
}