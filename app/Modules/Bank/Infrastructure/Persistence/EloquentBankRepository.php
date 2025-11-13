<?php

namespace App\Modules\Bank\Infrastructure\Persistence;

use App\Modules\Bank\Domain\Entities\Bank;
use App\Modules\Bank\Domain\Interfaces\BankRepositoryInterface;
use App\Modules\Bank\Infrastructure\Models\EloquentBank;


class EloquentBankRepository implements BankRepositoryInterface
{

    public function findAll(): array
    {
        $payload = auth('api')->payload();
        $companyId = $payload->get('company_id');

        $eloquentBanks = EloquentBank::with('currencyType', 'user', 'company')
            ->where('company_id', $companyId)
            ->get();

        return $eloquentBanks->map(function ($eloquentBank) {

            return new Bank(
                id: $eloquentBank->id,
                name: $eloquentBank->name,
                account_number: $eloquentBank->account_number,
                currency_type: $eloquentBank->currencyType->toDomain($eloquentBank->currencyType),
                user: $eloquentBank->user->toDomain($eloquentBank->user),
                date_at: $eloquentBank->created_at,
                company: $eloquentBank->company->toDomain($eloquentBank->company),
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
            'company_id' => $bank->getCompany()->getId()
        ]);
        $eloquentBank->refresh();

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

    public function findById(int $id): ?Bank
    {
        $eloquentBank = EloquentBank::with('currencyType', 'user', 'company')->find($id);

        if (!$eloquentBank) {
            return null;
        }

        return new Bank(
            id: $eloquentBank->id,
            name: $eloquentBank->name,
            account_number: $eloquentBank->account_number,
            currency_type: $eloquentBank->currencyType->toDomain($eloquentBank->currencyType),
            user: $eloquentBank->user->toDomain($eloquentBank->user),
            date_at: $eloquentBank->created_at,
            company: $eloquentBank->company->toDomain($eloquentBank->company),
            status: $eloquentBank->status,
        );
    }

    public function update(Bank $bank): ?Bank
    {
        $eloquentBank = EloquentBank::with('currencyType', 'user', 'company')->find($bank->getId());

        if (!$eloquentBank) {
            return null;
        }

        $eloquentBank->update([
            'name' => $bank->getName(),
            'account_number' => $bank->getAccountNumber(),
            'currency_type_id' => $bank->getCurrencyType()->getId(),
            'user_id' => $bank->getUser()->getId(),
            'company_id' => $bank->getCompany()->getId()
        ]);

        return new Bank(
            id: $eloquentBank->id,
            name: $eloquentBank->name,
            account_number: $eloquentBank->account_number,
            currency_type: $eloquentBank->currencyType->toDomain($eloquentBank->currencyType),
            user: $eloquentBank->user->toDomain($eloquentBank->user),
            date_at: $eloquentBank->created_at,
            company: $eloquentBank->company->toDomain($eloquentBank->company),
            status: $eloquentBank->status,
        );
    }

    public function updateStatus(int $bankId, int $status): void
    {
        EloquentBank::where('id', $bankId)->update(['status' => $status]);
    }
}
