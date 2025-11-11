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

readonly class UpdateCreditNoteUseCase
{
    public function __construct(
        private readonly SaleRepositoryInterface $saleRepository,
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly NoteReasonRepositoryInterface $noteReasonRepository
    ){}

    public function execute(SaleCreditNoteDTO $saleCreditNoteDTO, $id): ?SaleCreditNote
    {
        $companyUseCase = new FindByIdCompanyUseCase($this->companyRepository);
        $company = $companyUseCase->execute($saleCreditNoteDTO->company_id);

        $userUseCase = new GetUserByIdUseCase($this->userRepository);
        $user = $userUseCase->execute($saleCreditNoteDTO->user_id);

        $noteReasonUseCase = new FindByIdNoteReasonUseCase($this->noteReasonRepository);
        $noteReason = $noteReasonUseCase->execute($saleCreditNoteDTO->note_reason_id);

        $saleCreditNote = new SaleCreditNote(
            id: $id,
            company: $company,
            branch: null,
            documentType: null,
            serie: null,
            document_number: null,
            parallel_rate: null,
            customer: null,
            date: $saleCreditNoteDTO->date,
            due_date: $saleCreditNoteDTO->due_date,
            days: $saleCreditNoteDTO->days,
            user: $user,
            paymentType: null,
            currencyType: null,
            subtotal: $saleCreditNoteDTO->subtotal,
            igv: $saleCreditNoteDTO->igv,
            total: $saleCreditNoteDTO->total,
            saldo: $saleCreditNoteDTO->total,
            amount_amortized: 0,
            status: 1,
            payment_status: 0,
            is_locked: null,
            reference_document_type_id: null,
            reference_serie: null,
            reference_correlative: null,
            note_reason: $noteReason
        );

        return $this->saleRepository->updateCreditNote($saleCreditNote);
    }
}
