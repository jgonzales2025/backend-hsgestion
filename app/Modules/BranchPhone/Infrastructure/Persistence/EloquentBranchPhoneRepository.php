<?php

namespace App\Modules\BranchPhone\Infrastructure\Persistence;

use App\Modules\BranchPhone\Domain\Entities\BranchPhone;
use App\Modules\BranchPhone\Domain\Interfaces\BranchPhoneRepositoryInterface;
use App\Modules\BranchPhone\Infrastructure\Model\EloquentBranchPhone;

class EloquentBranchPhoneRepository implements BranchPhoneRepositoryInterface{
     
     public function findAllBranchPhone(): array
    {
        $branchPhones = EloquentBranchPhone::with('branch')->get();

        return $branchPhones->map(function ($phone) {
            return new BranchPhone(
                id: $phone->id,
                branch_id: $phone->branch_id,
                phone: $phone->phone
            );
        })->toArray();
    }
public function findByBranchId(int $branchId): array
    {
        $phones = EloquentBranchPhone::where('branch_id', $branchId)->get();

        return $phones->map(function ($phone) {
            return new BranchPhone(
                id: $phone->id,
                branch_id: $phone->branch_id,
                phone: $phone->phone
            );
        })->toArray();
    }
        public function save(BranchPhone $branchPhone): BranchPhone
    {
        $model = EloquentBranchPhone::create([
            'branch_id' => $branchPhone->getBranchId(),
            'phone' => $branchPhone->getPhone(),
        ]);

        return new BranchPhone(
            id: $model->id,
            branch_id: $model->branch_id,
            phone: $model->phone
        );
    }
}