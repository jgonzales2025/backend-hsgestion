<?php

namespace App\Modules\Bank\Application\UseCases;

use App\Modules\Bank\Application\DTOs\BankDTO;
use App\Modules\Bank\Domain\Entities\Bank;
use App\Modules\Bank\Domain\Interfaces\BankRepositoryInterface;
use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\CurrencyType\Application\UseCases\FindByIdCurrencyTypeUseCase;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\User\Application\UseCases\GetUserByIdUseCase;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;

readonly class UpdateBankUseCase
{
    public function __construct(
        private readonly BankRepositoryInterface $bankRepository,
        private readonly CurrencyTypeRepositoryInterface $currencyTypeRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly CompanyRepositoryInterface $companyRepository,
    ){}

    public function execute($id, BankDTO $bankDTO): ?Bank
    {
        $currencyUseCase = new FindByIdCurrencyTypeUseCase($this->currencyTypeRepository);
        $currencyType = $currencyUseCase->execute($bankDTO->currency_type_id);

        $userUseCase = new GetUserByIdUseCase($this->userRepository);
        $user = $userUseCase->execute($bankDTO->user_id);

        $companyUseCase = new FindByIdCompanyUseCase($this->companyRepository);
        $company = $companyUseCase->execute($bankDTO->company_id);

        $bank = new Bank(
            id: $id,
            name: $bankDTO->name,
            account_number: $bankDTO->account_number,
            currency_type: $currencyType,
            user: $user,
            date_at: null,
            company: $company,
            status: $bankDTO->status,
        );

        return $this->bankRepository->update($bank);
    }
}
