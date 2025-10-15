<?php

namespace App\Modules\Bank\Infrastructure\Persistence;

use App\Modules\Bank\Domain\Entities\Bank;
use App\Modules\Bank\Domain\Interfaces\BankRepositoryInterface;
use App\Modules\Bank\Infrastructure\Models\EloquentBank;
use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use App\Modules\CurrencyType\Infrastructure\Models\EloquentCurrencyType;
use App\Modules\User\Infrastructure\Model\EloquentUser;

class EloquentBankRepository implements BankRepositoryInterface
{

    public function findAll(): array
    {
        $eloquentBanks = EloquentBank::with('currencyType', 'user', 'company')->get();

        return $eloquentBanks->map(function ($eloquentBank) {
            $currencyType = EloquentCurrencyType::find($eloquentBank->currency_type_id);
            $user = EloquentUser::find($eloquentBank->user_id);
            $company = EloquentCompany::find($eloquentBank->company_id);

            return new Bank(
                id: $eloquentBank->id,
                name: $eloquentBank->name,
                account_number: $eloquentBank->account_number,
                currency_type: $currencyType->toDomain($currencyType),
                user: $user->toDomain($user),
                date_at: $eloquentBank->created_at,
                company: $company->toDomain($company),
                status: $eloquentBank->status,
            );
        })->toArray();
    }

    public function save(Bank $bank): ?Bank
    {
        $eloquentBank = EloquentBank::create([
            'name' => $bank->getName(),
            'account_number' => $bank->getAccountNumber(),
            'currency_type_id' => $bank->getCurrencyType()->getId(),
            'user_id' => $bank->getUser()->getId(),
            'company_id' => $bank->getCompany()->getId(),
            'status' => $bank->getStatus(),
        ]);

        return new Bank(
            id: $eloquentBank->id,
            name: $eloquentBank->name,
            account_number: $eloquentBank->account_number,
            currency_type: $bank->getCurrencyType(),
            user: $bank->getUser(),
            date_at: $eloquentBank->created_at,
            company: $bank->getCompany(),
            status: $eloquentBank->status,
        );
    }
}
