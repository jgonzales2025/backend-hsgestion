<?php

namespace App\Modules\DispatchNotes\Application\UseCases;

use App\Modules\Branch\Application\UseCases\FindByIdBranchUseCase;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\DispatchNotes\application\DTOS\TransferOrderDTO;
use App\Modules\DispatchNotes\Domain\Interfaces\TransferOrderRepositoryInterface;
use App\Modules\EmissionReason\Application\UseCases\FindByIdEmissionReasonUseCase;
use App\Modules\EmissionReason\Domain\Interfaces\EmissionReasonRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;
use App\Modules\DispatchNotes\Domain\Entities\TransferOrder;

class CreateTransferOrderUseCase
{
    public function __construct(
        private TransferOrderRepositoryInterface $transferOrderRepository,
        private EmissionReasonRepositoryInterface $emissionReasonRepository,
        private BranchRepositoryInterface $branchRepository,
        private CompanyRepositoryInterface $companyRepository,
        private DocumentNumberGeneratorService $documentNumberGeneratorService,
    ) {}

    public function execute(TransferOrderDTO $dto): TransferOrder
    {
        $lastDocumentNumber = $this->transferOrderRepository->getLastDocumentNumber($dto->serie);
        $correlative = $this->documentNumberGeneratorService->generateNextNumber($lastDocumentNumber);

        $emissionReasonUseCase = new FindByIdEmissionReasonUseCase($this->emissionReasonRepository);
        $emissionReason = $emissionReasonUseCase->execute($dto->emission_reason_id);

        $branchUseCase = new FindByIdBranchUseCase($this->branchRepository);
        $branch = $branchUseCase->execute($dto->branch_id);

        $companyUseCase = new FindByIdCompanyUseCase($this->companyRepository);
        $company = $companyUseCase->execute($dto->company_id);

        $destinationBranchUseCase = new FindByIdBranchUseCase($this->branchRepository);
        $destinationBranch = $destinationBranchUseCase->execute($dto->destination_branch_id);

        $transferOrder = new TransferOrder(
            id: null,
            company: $company,
            branch: $branch,
            serie: $dto->serie,
            correlative: $correlative,
            emission_reason: $emissionReason,
            destination_branch: $destinationBranch,
            observations: $dto->observations
        );
        return $this->transferOrderRepository->save($transferOrder);
        
    }
}