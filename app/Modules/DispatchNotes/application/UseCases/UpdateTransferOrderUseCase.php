<?php

namespace App\Modules\DispatchNotes\application\UseCases;

use App\Modules\Branch\Application\UseCases\FindByIdBranchUseCase;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\DispatchNotes\application\DTOS\UpdateTransferOrderDTO;
use App\Modules\DispatchNotes\Domain\Entities\TransferOrder;
use App\Modules\DispatchNotes\Domain\Interfaces\TransferOrderRepositoryInterface;
use App\Modules\EmissionReason\Application\UseCases\FindByIdEmissionReasonUseCase;
use App\Modules\EmissionReason\Domain\Interfaces\EmissionReasonRepositoryInterface;

class UpdateTransferOrderUseCase
{
    public function __construct(
        private readonly TransferOrderRepositoryInterface $transferOrderRepository,
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly EmissionReasonRepositoryInterface $emissionReasonRepository
    ) {
    }

    public function execute(int $id, UpdateTransferOrderDTO $updateTransferOrderDTO)
    {
        $branchUseCase = new FindByIdBranchUseCase($this->branchRepository);
        $branch = $branchUseCase->execute($updateTransferOrderDTO->branch_id);

        $emissionReasonUseCase = new FindByIdEmissionReasonUseCase($this->emissionReasonRepository);
        $emissionReason = $emissionReasonUseCase->execute($updateTransferOrderDTO->emission_reason_id);

        $destinationBranchUseCase = new FindByIdBranchUseCase($this->branchRepository);
        $destinationBranch = $destinationBranchUseCase->execute($updateTransferOrderDTO->destination_branch_id);
        
        $transferOrder = new TransferOrder(
            id: $id,
            company: null,
            branch: $branch,
            serie: null,
            correlative: null,
            emission_reason: $emissionReason,
            destination_branch: $destinationBranch,
            observations: $updateTransferOrderDTO->observations
        );
        
        $this->transferOrderRepository->update($id, $transferOrder);
    }
}