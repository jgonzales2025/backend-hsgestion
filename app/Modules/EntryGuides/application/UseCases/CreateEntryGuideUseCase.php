<?php

namespace App\Modules\EntryGuides\Application\UseCases;

use App\Modules\Branch\Application\UseCases\FindByIdBranchUseCase;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\Customer\Application\UseCases\FindByIdCustomerUseCase;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\EntryGuides\Application\DTOS\EntryGuideDTO;
use App\Modules\EntryGuides\Domain\Entities\EntryGuide;
use App\Modules\EntryGuides\Domain\Interfaces\EntryGuideRepositoryInterface;
use App\Modules\IngressReason\Application\UseCases\FindByIdIngressReasonUseCase;
use App\Modules\IngressReason\Domain\Interfaces\IngressReasonRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;

class CreateEntryGuideUseCase{
    public function __construct(
        private readonly EntryGuideRepositoryInterface $entryGuideRepositoryInterface,
        private readonly CompanyRepositoryInterface $companyRepositoryInterface,
        private readonly BranchRepositoryInterface $branchRepositoryInterface,
        private readonly CustomerRepositoryInterface $customerRepositoryInterface,
        private readonly IngressReasonRepositoryInterface $ingressReasonRepositoryInterface,
        private readonly DocumentNumberGeneratorService $documentNumberGeneratorService,
    ){}

    public function execute(EntryGuideDTO $entryGuideDTO):?EntryGuide
    {
        $lastDocumentNumber = $this->entryGuideRepositoryInterface->getLastDocumentNumber($entryGuideDTO->serie);
        $entryGuideDTO->correlative = $this->documentNumberGeneratorService->generateNextNumber($lastDocumentNumber);

        $companyUseCase = new FindByIdCompanyUseCase($this->companyRepositoryInterface);
        $company = $companyUseCase->execute($entryGuideDTO->cia_id);

        $branchUseCase = new FindByIdBranchUseCase($this->branchRepositoryInterface);
        $branch = $branchUseCase->execute($entryGuideDTO->branch_id);

        $customerUseCase = new FindByIdCustomerUseCase($this->customerRepositoryInterface);
        $customer = $customerUseCase->execute($entryGuideDTO->customer_id);

        $ingressReasonUseCase = new FindByIdIngressReasonUseCase($this->ingressReasonRepositoryInterface);
        $ingressReason = $ingressReasonUseCase->execute($entryGuideDTO->ingress_reason_id);

        $entryGuide = new EntryGuide(
            id:null,
            cia: $company,
            branch: $branch,
            serie: $entryGuideDTO->serie,
            correlative: $entryGuideDTO->correlative,
            date: $entryGuideDTO->date,
            customer: $customer,
            observations: $entryGuideDTO->observations,
            ingressReason: $ingressReason,
            reference_po_serie: $entryGuideDTO->reference_po_serie,
            reference_po_correlative: $entryGuideDTO->reference_po_correlative,
            status: null,
        );

        return $this->entryGuideRepositoryInterface->save($entryGuide);
    }

}