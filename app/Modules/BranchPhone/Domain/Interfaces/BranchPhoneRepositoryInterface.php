<?php

namespace App\Modules\BranchPhone\Domain\Interfaces;

use App\Modules\BranchPhone\Domain\Entities\BranchPhone;

interface BranchPhoneRepositoryInterface{
    public function findAllBranchPhone(): array;
     public function findByBranchId(int $branchId): array;
      public function save(BranchPhone $branchPhone): BranchPhone;

    
}