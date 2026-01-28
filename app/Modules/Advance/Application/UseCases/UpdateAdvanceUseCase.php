<?php

namespace App\Modules\Advance\Application\UseCases;

use App\Modules\Advance\Application\DTOs\AdvanceDTO;
use App\Modules\Advance\Domain\Entities\Advance;
use App\Modules\Advance\Domain\Entities\UpdateAdvance;
use App\Modules\Advance\Domain\Interfaces\AdvanceRepositoryInterface;
use App\Modules\Bank\Application\UseCases\FindByIdBankUseCase;
use App\Modules\Bank\Domain\Interfaces\BankRepositoryInterface;
use App\Modules\CurrencyType\Application\UseCases\FindByIdCurrencyTypeUseCase;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\Customer\Application\UseCases\FindByIdCustomerUseCase;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\PaymentMethod\Application\UseCases\FindByIdPaymentMethodUseCase;
use App\Modules\PaymentMethod\Domain\Interfaces\PaymentMethodRepositoryInterface;

class UpdateAdvanceUseCase
{
    public function __construct(
        private readonly AdvanceRepositoryInterface $advanceRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly PaymentMethodRepositoryInterface $paymentMethodRepository,
        private readonly BankRepositoryInterface $bankRepository,
        private readonly CurrencyTypeRepositoryInterface $currencyTypeRepository,
    ){}

    public function execute(AdvanceDTO $advanceDTO, int $id): void
    {
        $customerUseCase = new FindByIdCustomerUseCase($this->customerRepository);
        $customer = $customerUseCase->execute($advanceDTO->customer_id);

        $paymentMethodUseCase = new FindByIdPaymentMethodUseCase($this->paymentMethodRepository);
        $paymentMethod = $paymentMethodUseCase->execute($advanceDTO->payment_method_id);

        $bankUseCase = new FindByIdBankUseCase($this->bankRepository);
        $bank = $bankUseCase->execute($advanceDTO->bank_id);

        $currencyTypeUseCase = new FindByIdCurrencyTypeUseCase($this->currencyTypeRepository);
        $currencyType = $currencyTypeUseCase->execute($advanceDTO->currency_type_id);

        $advance = new UpdateAdvance(
            id: $id,
            customer: $customer,
            payment_method: $paymentMethod,
            bank: $bank,
            operation_number: $advanceDTO->operation_number,
            operation_date: $advanceDTO->operation_date,
            parallel_rate: $advanceDTO->parallel_rate,
            currency_type: $currencyType,
            amount: $advanceDTO->amount
        );
        $this->advanceRepository->update($advance);
    }
}