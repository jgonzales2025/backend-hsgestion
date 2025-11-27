<?php

namespace App\Modules\Advance\Application\UseCases;

use App\Modules\Advance\Application\DTOs\AdvanceDTO;
use App\Modules\Advance\Domain\Entities\Advance;
use App\Modules\Advance\Domain\Interfaces\AdvanceRepositoryInterface;
use App\Modules\Bank\Application\UseCases\FindByIdBankUseCase;
use App\Modules\Bank\Domain\Interfaces\BankRepositoryInterface;
use App\Modules\CurrencyType\Application\UseCases\FindByIdCurrencyTypeUseCase;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\Customer\Application\UseCases\FindByIdCustomerUseCase;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\PaymentMethod\Application\UseCases\FindByIdPaymentMethodUseCase;
use App\Modules\PaymentMethod\Domain\Interfaces\PaymentMethodRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;

class CreateAdvanceUseCase
{
    public function __construct(
        private AdvanceRepositoryInterface $advanceRepository,
        private CustomerRepositoryInterface $customerRepository,
        private PaymentMethodRepositoryInterface $paymentMethodRepository,
        private BankRepositoryInterface $bankRepository,
        private CurrencyTypeRepositoryInterface $currencyTypeRepository,
        private DocumentNumberGeneratorService $documentNumberGeneratorService
        ) {}

    public function execute(AdvanceDTO $advance_dto): Advance
    {
        $lastDocumentNumber = $this->advanceRepository->getLastDocumentNumber();
        $correlative = $this->documentNumberGeneratorService->generateNextNumber($lastDocumentNumber);

        $customerUseCase = new FindByIdCustomerUseCase($this->customerRepository);
        $customer = $customerUseCase->execute($advance_dto->customer_id);

        $paymentMethodUseCase = new FindByIdPaymentMethodUseCase($this->paymentMethodRepository);
        $paymentMethod = $paymentMethodUseCase->execute($advance_dto->payment_method_id);

        $bankUseCase = new FindByIdBankUseCase($this->bankRepository);
        $bank = $bankUseCase->execute($advance_dto->bank_id);

        $currencyTypeUseCase = new FindByIdCurrencyTypeUseCase($this->currencyTypeRepository);
        $currencyType = $currencyTypeUseCase->execute($advance_dto->currency_type_id);

        $advance = new Advance(
            id: $advance_dto->id,
            correlative: $correlative,
            customer: $customer,
            payment_method: $paymentMethod,
            bank: $bank,
            operation_number: $advance_dto->operation_number,
            operation_date: $advance_dto->operation_date,
            parallel_rate: $advance_dto->parallel_rate,
            currency_type: $currencyType,
            amount: $advance_dto->amount
        );

        return $this->advanceRepository->save($advance);
    }
}