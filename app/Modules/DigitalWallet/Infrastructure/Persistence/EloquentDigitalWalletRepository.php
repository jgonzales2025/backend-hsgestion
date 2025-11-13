<?php

namespace App\Modules\DigitalWallet\Infrastructure\Persistence;

use App\Modules\DigitalWallet\Domain\Entities\DigitalWallet;
use App\Modules\DigitalWallet\Domain\Interfaces\DigitalWalletRepositoryInterface;
use App\Modules\DigitalWallet\Infrastructure\Models\EloquentDigitalWallet;

class EloquentDigitalWalletRepository implements DigitalWalletRepositoryInterface
{

    public function findAll(): array
    {
        $payload = auth('api')->payload();
        $companyId = $payload->get('company_id');

        $eloquentDigitalWallets = EloquentDigitalWallet::with('user', 'company')
            ->where('company_id', $companyId)
            ->orderBy('created_at', 'desc')
            ->get();

        return $eloquentDigitalWallets->map(function ($eloquentDigitalWallet) {
            return new DigitalWallet(
                id: $eloquentDigitalWallet->id,
                name: $eloquentDigitalWallet->name,
                phone: $eloquentDigitalWallet->phone,
                company: $eloquentDigitalWallet->company->toDomain($eloquentDigitalWallet->company),
                user: $eloquentDigitalWallet->user->toDomain($eloquentDigitalWallet->user),
                status: $eloquentDigitalWallet->status,
            );
        })->toArray();
    }

    public function save(DigitalWallet $digitalWallet): ?DigitalWallet
    {
        $eloquentDigitalWallet = EloquentDigitalWallet::create([
            'name' => $digitalWallet->getName(),
            'phone' => $digitalWallet->getPhone(),
            'company_id' => $digitalWallet->getCompany()->getId(),
            'user_id' => $digitalWallet->getUser()->getId()
        ]);
        $eloquentDigitalWallet->refresh();

        return new DigitalWallet(
            id: $eloquentDigitalWallet->id,
            name: $eloquentDigitalWallet->name,
            phone: $eloquentDigitalWallet->phone,
            company: $digitalWallet->getCompany(),
            user: $digitalWallet->getUser(),
            status: $eloquentDigitalWallet->status,
        );
    }

    public function findById(int $id): ?DigitalWallet
    {
        $eloquentDigitalWallet = EloquentDigitalWallet::with('user', 'company')->find($id);

        if (!$eloquentDigitalWallet) {
            return null;
        }

        return new DigitalWallet(
            id: $eloquentDigitalWallet->id,
            name: $eloquentDigitalWallet->name,
            phone: $eloquentDigitalWallet->phone,
            company: $eloquentDigitalWallet->company->toDomain($eloquentDigitalWallet->company),
            user: $eloquentDigitalWallet->user->toDomain($eloquentDigitalWallet->user),
            status: $eloquentDigitalWallet->status,
        );
    }

    public function update(DigitalWallet $digitalWallet): ?DigitalWallet
    {
        $eloquentDigitalWallet = EloquentDigitalWallet::with('user', 'company')->find($digitalWallet->getId());

        if (!$eloquentDigitalWallet) {
            return null;
        }

        $eloquentDigitalWallet->update([
            'name' => $digitalWallet->getName(),
            'phone' => $digitalWallet->getPhone(),
            'company_id' => $digitalWallet->getCompany()->getId(),
            'user_id' => $digitalWallet->getUser()->getId()
        ]);

        return new DigitalWallet(
            id: $eloquentDigitalWallet->id,
            name: $eloquentDigitalWallet->name,
            phone: $eloquentDigitalWallet->phone,
            company: $eloquentDigitalWallet->company->toDomain($eloquentDigitalWallet->company),
            user: $eloquentDigitalWallet->user->toDomain($eloquentDigitalWallet->user),
            status: $eloquentDigitalWallet->status
        );
    }

    public function updateStatus(int $digitalWalletId, int $status): void
    {
        EloquentDigitalWallet::where('id', $digitalWalletId)->update(['status' => $status]);
    }
}
