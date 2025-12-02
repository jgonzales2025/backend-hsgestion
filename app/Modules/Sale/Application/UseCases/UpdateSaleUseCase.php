<?php

namespace App\Modules\Sale\Application\UseCases;

use App\Modules\Branch\Application\UseCases\FindByIdBranchUseCase;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\CurrencyType\Application\UseCases\FindByIdCurrencyTypeUseCase;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\Customer\Application\UseCases\FindByIdCustomerUseCase;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\DocumentType\Application\UseCases\FindByIdDocumentTypeUseCase;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\PaymentType\Application\UseCases\FindByIdPaymentTypeUseCase;
use App\Modules\PaymentType\Domain\Interfaces\PaymentTypeRepositoryInterface;
use App\Modules\Sale\Application\DTOs\SaleDTO;
use App\Modules\Sale\Domain\Entities\Sale;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;
use App\Modules\User\Application\UseCases\GetUserByIdUseCase;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;

readonly class UpdateSaleUseCase
{
    public function __construct(
        private readonly SaleRepositoryInterface $saleRepository,
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly CurrencyTypeRepositoryInterface $currencyTypeRepository,
        private readonly DocumentTypeRepositoryInterface $documentTypeRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly PaymentTypeRepositoryInterface $paymentTypeRepository,
    ){}

    public function execute(SaleDTO $saleDTO, Sale $sale): ?Sale
    {
        $company = $this->companyRepository->findById($saleDTO->company_id);
        $branch = $this->branchRepository->findById($saleDTO->branch_id);
        $documentType = $this->documentTypeRepository->findById($saleDTO->document_type_id);
        $customer = $this->customerRepository->findById($saleDTO->customer_id);
        $user = $this->userRepository->findById($saleDTO->user_id);
        $userSale = $this->userRepository->findById($saleDTO->user_sale_id ?? $saleDTO->user_id);
        $paymentType = $this->paymentTypeRepository->findById($saleDTO->payment_type_id);
        $currencyType = $this->currencyTypeRepository->findById($saleDTO->currency_type_id);
        $userAuthorized = $this->userRepository->findById($saleDTO->user_authorized_id);

        $sale = new Sale(
            id: $sale->getId(),
            company: $company,
            branch: $branch,
            documentType: $documentType,
            serie: $sale->getSerie(),
            document_number: $sale->getDocumentNumber(),
            parallel_rate: $saleDTO->parallel_rate,
            customer: $customer,
            date: $saleDTO->date,
            due_date: $saleDTO->due_date,
            days: $saleDTO->days,
            user: $user,
            user_sale: $userSale,
            paymentType: $paymentType,
            observations: $saleDTO->observations,
            currencyType: $currencyType,
            subtotal: $saleDTO->subtotal,
            igv: $saleDTO->igv,
            total: $saleDTO->total,
            saldo: $saleDTO->saldo,
            amount_amortized: $saleDTO->amount_amortized,
            status: null,
            payment_status: $saleDTO->payment_status,
            is_locked: $sale->getIsLocked(),
            id_prof: $saleDTO->id_prof,
            serie_prof: $saleDTO->serie_prof,
            correlative_prof: $saleDTO->correlative_prof,
            purchase_order: $saleDTO->purchase_order,
            user_authorized: $userAuthorized,
            coddetrac: $saleDTO->coddetrac,
            pordetrac: $saleDTO->pordetrac,
            impdetracs: $saleDTO->impdetracs,
            impdetracd: $saleDTO->impdetracd,
            stretencion: $saleDTO->stretencion,
            porretencion: $saleDTO->porretencion,
            impretens: $saleDTO->impretens,
            impretend: $saleDTO->impretend
        );

        return $this->saleRepository->update($sale);
    }
}
