<?php

namespace App\Modules\EntryGuides\Application\UseCases;

use App\Modules\Branch\Application\UseCases\FindByIdBranchUseCase;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\Customer\Application\UseCases\FindByIdCustomerUseCase;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\EmissionReason\Application\UseCases\FindByIdEmissionReasonUseCase;
use App\Modules\EntryGuides\Application\DTOS\EntryGuideDTO;
use App\Modules\EntryGuides\Domain\Entities\EntryGuide;
use App\Modules\EntryGuides\Domain\Interfaces\EntryGuideRepositoryInterface;


class UpdateEntryGuideUseCase{
    public function __construct(
        private readonly EntryGuideRepositoryInterface $entryGuideRepositoryInterface,
        private readonly CompanyRepositoryInterface $companyRepositoryInterface,
        private readonly BranchRepositoryInterface $branchRepositoryInterface,
        private readonly CustomerRepositoryInterface $customerRepositoryInterface,
    ){}

    public function execute(EntryGuideDTO $entryGuideDTO, $id):?EntryGuide
    {
        $companyUseCase = new FindByIdCompanyUseCase($this->companyRepositoryInterface);
        $company = $companyUseCase->execute($entryGuideDTO->cia_id);
        if (!$company) {
            return $company = null;
        }
        $branchUseCase = new FindByIdBranchUseCase($this->branchRepositoryInterface);
        $branch = $branchUseCase->execute($entryGuideDTO->branch_id);
        if (!$branch) {
            return $branch = null;
        }
        $customerUseCase = new FindByIdCustomerUseCase($this->customerRepositoryInterface);
        $customer = $customerUseCase->execute($entryGuideDTO->customer_id);
        if (!$customer) {
            return $customer = null;
        }
        // $entryGuideUseCase = new FindByIdEmissionReasonUseCase(entryGuideRepositoryInterface: $this->entryGuideRepositoryInterface);
        // $entryGuide = $entryGuideUseCase->execute($entryGuideDTO->ingress_reason_id);
        // if ($entryGuide) {
        //     return $entryGuide = null;
        // }
       
        
        $entryGuide = new EntryGuide(
            id:$id,
            cia: $company,
            branch: $branch,
            serie: $entryGuideDTO->serie,
            correlative: $entryGuideDTO->correlative,
            date: $entryGuideDTO->date,
            customer: $customer,
            guide_serie_supplier: $entryGuideDTO->guide_serie_supplier,
            guide_correlative_supplier: $entryGuideDTO->guide_correlative_supplier,
            invoice_serie_supplier: $entryGuideDTO->invoice_serie_supplier,
            invoice_correlative_supplier: $entryGuideDTO->invoice_correlative_supplier,
            observations: $entryGuideDTO->observations,
            ingressReason: null,
            reference_serie: $entryGuideDTO->reference_serie,
            reference_correlative: $entryGuideDTO->reference_correlative,
            status: $entryGuideDTO->status,
        );
        return $this->entryGuideRepositoryInterface->update($entryGuide);
    }

}