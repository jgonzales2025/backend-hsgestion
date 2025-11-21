<?php

namespace App\Modules\EmissionReason\Application\UseCases;

use App\Modules\EmissionReason\Domain\Interfaces\EmissionReasonRepositoryInterface;

class FindAllForTransferOrderUseCase
{
    public function __construct(private EmissionReasonRepositoryInterface $emissionReasonRepository){}

    public function execute(): array
    {
        return $this->emissionReasonRepository->findAllForTransferOrders();
    }
}