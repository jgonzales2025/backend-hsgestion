<?php

namespace App\Modules\EntryGuides\Application\UseCases;

use App\Modules\Branch\Application\UseCases\FindByIdBranchUseCase;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\CurrencyType\Application\UseCases\FindByIdCurrencyTypeUseCase;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\Customer\Application\UseCases\FindByIdCustomerUseCase;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\EmissionReason\Application\UseCases\FindByIdEmissionReasonUseCase;
use App\Modules\EntryGuides\Application\DTOS\EntryGuideDTO;
use App\Modules\EntryGuides\Domain\Entities\EntryGuide;
use App\Modules\EntryGuides\Domain\Interfaces\EntryGuideRepositoryInterface;
use App\Modules\IngressReason\Application\UseCases\FindByIdIngressReasonUseCase;
use App\Modules\IngressReason\Domain\Interfaces\IngressReasonRepositoryInterface;

class UpdateEntryGuideUseCase
{
    public function __construct(
        private readonly EntryGuideRepositoryInterface $entryGuideRepositoryInterface,
        private readonly CompanyRepositoryInterface $companyRepositoryInterface,
        private readonly BranchRepositoryInterface $branchRepositoryInterface,
        private readonly CustomerRepositoryInterface $customerRepositoryInterface,
        private readonly IngressReasonRepositoryInterface $ingressReasonRepositoryInterface,
        private readonly CurrencyTypeRepositoryInterface $currencyTypeRepositoryInterface,
    ) {}

    public function execute(EntryGuideDTO $entryGuideDTO, $id): ?EntryGuide
    {
        $companyUseCase = new FindByIdCompanyUseCase($this->companyRepositoryInterface);
        $company = $companyUseCase->execute($entryGuideDTO->cia_id);

        $branchUseCase = new FindByIdBranchUseCase($this->branchRepositoryInterface);
        $branch = $branchUseCase->execute($entryGuideDTO->branch_id);

        $customerUseCase = new FindByIdCustomerUseCase($this->customerRepositoryInterface);
        $customer = $customerUseCase->execute($entryGuideDTO->customer_id);

        $ingressReasonUseCase = new FindByIdIngressReasonUseCase($this->ingressReasonRepositoryInterface);
        $ingressReason = $ingressReasonUseCase->execute($entryGuideDTO->ingress_reason_id);

        $currencyUseCase = new FindByIdCurrencyTypeUseCase($this->currencyTypeRepositoryInterface);
        $currency = $currencyUseCase->execute($entryGuideDTO->currency_id);

        $entryGuide = new EntryGuide(
            id: $id,
            cia: $company,
            branch: $branch,
            serie: $entryGuideDTO->serie ?? null,
            correlative: $entryGuideDTO->correlative ?? null,
            date: $entryGuideDTO->date,
            customer: $customer,
            observations: $entryGuideDTO->observations,
            ingressReason: $ingressReason,
            reference_serie: $entryGuideDTO->reference_serie ?? null,
            reference_correlative: $entryGuideDTO->reference_correlative ?? null,
            status: null,
            subtotal: $entryGuideDTO->subtotal,
            total_descuento: $entryGuideDTO->total_descuento,
            total: $entryGuideDTO->total,
            update_price: $entryGuideDTO->update_price,
            entry_igv: $entryGuideDTO->entry_igv,
            currency: $currency,
            includ_igv: $entryGuideDTO->includ_igv,
            reference_document_id: $entryGuideDTO->reference_document_id,
        );
        return $this->entryGuideRepositoryInterface->update($entryGuide);
    }
}
