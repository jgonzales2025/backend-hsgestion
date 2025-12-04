<?php

namespace App\Modules\Bank\Infrastructure\Persistence;

use App\Modules\Bank\Domain\Entities\Bank;
use App\Modules\Bank\Domain\Interfaces\BankRepositoryInterface;
use App\Modules\Bank\Infrastructure\Models\EloquentBank;


class EloquentBankRepository implements BankRepositoryInterface
{

    public function findAll(?string $description, ?int $status, ?int $company_id, ?int $currency_type_id)
    {
        $payload = auth('api')->payload();
        $companyId = $payload->get('company_id');

        $eloquentBanks = EloquentBank::with('currencyType', 'user', 'company')
            ->where('company_id', $companyId)
            ->when($description, fn($query) => $query->where('name', 'like', "%{$description}%")
                ->orWhere('account_number', 'like', "%{$description}%"))
            ->when($status !== null, fn($query) => $query->where('status', $status))
            ->when($company_id, fn($query) => $query->where('company_id', $company_id))
            ->when($currency_type_id, fn($query) => $query->where('currency_type_id', $currency_type_id))
            ->paginate(10);

        $eloquentBanks->getCollection()->transform(fn($eloquentBank) => (
            new Bank(
                id: $eloquentBank->id,
                name: $eloquentBank->name,
                account_number: $eloquentBank->account_number,
                currency_type: $eloquentBank->currencyType->toDomain($eloquentBank->currencyType),
                user: $eloquentBank->user->toDomain($eloquentBank->user),
                date_at: $eloquentBank->created_at,
                company: $eloquentBank->company->toDomain($eloquentBank->company),
                status: $eloquentBank->status,
            ))
        );

        return $eloquentBanks;
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
