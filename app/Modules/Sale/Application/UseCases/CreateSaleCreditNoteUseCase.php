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
use App\Modules\PaymentType\Application\UseCases\FindByIdPaymentTypeUseCase;
use App\Modules\PaymentType\Domain\Interfaces\PaymentTypeRepositoryInterface;
use App\Modules\Sale\Application\DTOs\SaleCreditNoteDTO;
use App\Modules\Sale\Application\DTOs\SaleDTO;
use App\Modules\Sale\Domain\Entities\Sale;
use App\Modules\Sale\Domain\Entities\SaleCreditNote;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;
use App\Modules\User\Application\UseCases\GetUserByIdUseCase;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;
use Illuminate\Support\Facades\Log;

readonly class CreateSaleCreditNoteUseCase
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
        private readonly NoteReasonRepositoryInterface $noteReasonRepository,
        private readonly DocumentNumberGeneratorService $documentNumberGenerator
    ){}

    public function execute(SaleCreditNoteDTO $saleCreditNoteDTO): ?SaleCreditNote
    {
        $lastDocumentNumber = $this->saleRepository->getLastDocumentNumber($saleCreditNoteDTO->serie);
        $saleCreditNoteDTO->document_number = $this->documentNumberGenerator->generateNextNumber($lastDocumentNumber);

        $saleUseCase = new FindByDocumentSaleUseCase($this->saleRepository);
        $sale = $saleUseCase->execute($saleCreditNoteDTO->reference_document_type_id, $saleCreditNoteDTO->reference_serie, $saleCreditNoteDTO->reference_correlative);

        $companyUseCase = new FindByIdCompanyUseCase($this->companyRepository);
        $company = $companyUseCase->execute($saleCreditNoteDTO->company_id);

        $branchUseCase = new FindByIdBranchUseCase($this->branchRepository);
        $branch = $branchUseCase->execute($saleCreditNoteDTO->branch_id);

        $userUseCase = new GetUserByIdUseCase($this->userRepository);
        $user = $userUseCase->execute($saleCreditNoteDTO->user_id);

        $userSaleUseCase = new GetUserByIdUseCase($this->userRepository);
        $userSale = $userSaleUseCase->execute($sale->getUserSale()->getId());

        $currencyTypeUseCase = new FindByIdCurrencyTypeUseCase($this->currencyTypeRepository);
        $currencyType = $currencyTypeUseCase->execute($saleCreditNoteDTO->currency_type_id);

        $documentTypeUseCase = new FindByIdDocumentTypeUseCase($this->documentTypeRepository);
        $documentType = $documentTypeUseCase->execute($saleCreditNoteDTO->document_type_id);

        $customerUseCase = new FindByIdCustomerUseCase($this->customerRepository);
        $customer = $customerUseCase->execute($saleCreditNoteDTO->customer_id);

        $paymentTypeUseCase = new FindByIdPaymentTypeUseCase($this->paymentTypeRepository);
        $paymentType = $paymentTypeUseCase->execute($saleCreditNoteDTO->payment_type_id);

        $noteReasonUseCase = new FindByIdNoteReasonUseCase($this->noteReasonRepository);
        $noteReason = $noteReasonUseCase->execute($saleCreditNoteDTO->note_reason_id);

        $saleCreditNote = new SaleCreditNote(
            id: 0,
            company: $company,
            branch: $branch,
            documentType: $documentType,
            serie: $saleCreditNoteDTO->serie,
            document_number: $saleCreditNoteDTO->document_number,
            parallel_rate: $saleCreditNoteDTO->parallel_rate,
            customer: $customer,
            date: $saleCreditNoteDTO->date,
            due_date: $saleCreditNoteDTO->due_date,
            days: $saleCreditNoteDTO->days,
            user: $user,
            user_sale: $userSale,
            paymentType: $paymentType,
            currencyType: $currencyType,
            subtotal: $saleCreditNoteDTO->subtotal,
            igv: $saleCreditNoteDTO->igv,
            total: $saleCreditNoteDTO->total,
            saldo: $saleCreditNoteDTO->total,
            amount_amortized: 0,
            status: 1,
            payment_status: 0,
            is_locked: null,
            reference_document_type_id: $saleCreditNoteDTO->reference_document_type_id,
            reference_serie: $saleCreditNoteDTO->reference_serie,
            reference_correlative: $saleCreditNoteDTO->reference_correlative,
            note_reason: $noteReason
        );

        return $this->saleRepository->saveCreditNote($saleCreditNote);
    }
}
