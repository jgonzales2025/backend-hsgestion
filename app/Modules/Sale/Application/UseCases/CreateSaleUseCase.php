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
use App\Modules\NoteReason\Application\UseCases\FindByIdNoteReasonUseCase;
use App\Modules\NoteReason\Domain\Interfaces\NoteReasonRepositoryInterface;
use App\Modules\PaymentMethod\Application\UseCases\FindByIdPaymentMethodUseCase;
use App\Modules\PaymentMethod\Domain\Interfaces\PaymentMethodRepositoryInterface;
use App\Modules\PaymentType\Application\UseCases\FindByIdPaymentTypeUseCase;
use App\Modules\PaymentType\Domain\Interfaces\PaymentTypeRepositoryInterface;
use App\Modules\Sale\Application\DTOs\SaleDTO;
use App\Modules\Sale\Domain\Entities\Sale;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;
use App\Modules\User\Application\UseCases\GetUserByIdUseCase;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;

readonly class CreateSaleUseCase
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
        private readonly DocumentNumberGeneratorService $documentNumberGeneratorService,
        private readonly PaymentMethodRepositoryInterface $paymentMethodRepository
    ) {
    }

    public function execute(SaleDTO $saleDTO): ?Sale
    {
        $lastDocumentNumber = $this->saleRepository->getLastDocumentNumber($saleDTO->serie);
        $saleDTO->document_number = $this->documentNumberGeneratorService->generateNextNumber($lastDocumentNumber);

        $companyUseCase = new FindByIdCompanyUseCase($this->companyRepository);
        $company = $companyUseCase->execute($saleDTO->company_id);

        $branchUseCase = new FindByIdBranchUseCase($this->branchRepository);
        $branch = $branchUseCase->execute($saleDTO->branch_id);

        $userUseCase = new GetUserByIdUseCase($this->userRepository);
        $user = $userUseCase->execute($saleDTO->user_id);

        $userSaleUseCase = new GetUserByIdUseCase($this->userRepository);
        $userSale = $userSaleUseCase->execute($saleDTO->user_sale_id ?? $saleDTO->user_id);

        $currencyTypeUseCase = new FindByIdCurrencyTypeUseCase($this->currencyTypeRepository);
        $currencyType = $currencyTypeUseCase->execute($saleDTO->currency_type_id);

        $documentTypeUseCase = new FindByIdDocumentTypeUseCase($this->documentTypeRepository);
        $documentType = $documentTypeUseCase->execute($saleDTO->document_type_id);

        $customerUseCase = new FindByIdCustomerUseCase($this->customerRepository);
        $customer = $customerUseCase->execute($saleDTO->customer_id);

        $paymentTypeUseCase = new FindByIdPaymentTypeUseCase($this->paymentTypeRepository);
        $paymentType = $paymentTypeUseCase->execute($saleDTO->payment_type_id);

        $userAuthorizedUseCase = new GetUserByIdUseCase($this->userRepository);
        $userAuthorized = $userAuthorizedUseCase->execute($saleDTO->user_authorized_id);

        $sale = new Sale(
            id: 0,
            company: $company,
            branch: $branch,
            documentType: $documentType,
            serie: $saleDTO->serie,
            document_number: $saleDTO->document_number,
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
            saldo: $saleDTO->total,
            amount_amortized: 0,
            payment_status: 0,
            is_locked: null,
            reference_document_type_id: $saleDTO->reference_document_type_id,
            reference_serie: $saleDTO->reference_serie,
            reference_correlative: $saleDTO->reference_correlative,
            purchase_order: $saleDTO->purchase_order,
            user_authorized: $userAuthorized,
            credit_amount: $saleDTO->credit_amount,
            coddetrac: $saleDTO->coddetrac,
            pordetrac: $saleDTO->pordetrac,
            impdetracs: $saleDTO->impdetracs,
            impdetracd: $saleDTO->impdetracd,
            stretencion: $saleDTO->stretencion,
            porretencion: $saleDTO->porretencion,
            impretens: $saleDTO->impretens,
            impretend: $saleDTO->impretend,
            consignation_id: $saleDTO->consignation_id,
            igv_percentage: $saleDTO->igv_percentage
        );

        return $this->saleRepository->save($sale);
    }
}
