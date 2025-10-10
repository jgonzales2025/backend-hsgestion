<?php

namespace App\Modules\Branch\Infrastructure\Persistence;

use App\Modules\Branch\Domain\Entities\Branch;

use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Branch\Infrastructure\Models\EloquentBranch;


class EloquentBranchRepository implements BranchRepositoryInterface
{
    public function findAllBranchs(): array
    {
        $branchs = EloquentBranch::all();

        if ($branchs->isEmpty()) {
            return [];
        }

        return $branchs->map(fn($branch) => new Branch(
            id: $branch->id,
            cia_id: $branch->cia_id,
            name: $branch->name,
            address: $branch->address,
            email: $branch->email,
            start_date: $branch->start_date,
            serie: $branch->serie,
            status: $branch->status
        ))->toArray();
    }

    public function findById(int $id): ?Branch
    {
           $branch = EloquentBranch::with('company')->find($id);
           if (!$branch) {
              return null;
           }
                   return new Branch(
               id: $branch->id,
            cia_id: $branch->cia_id,
            name: $branch->name,
            address: $branch->address,
            email: $branch->email,
            start_date: $branch->start_date,
            serie: $branch->serie,
            status: $branch->status
        );
       
    }
       public function update(Branch $branch): void
    {
        $eloquentDriver = EloquentBranch::find($branch->getId());

        if (!$eloquentDriver) {
            throw new \Exception(" no encontrado branch");
        }

        $eloquentDriver->update([
            'cia_id'=> $branch->getCia_id(),
            'name'=> $branch->getName(),
            'address'=> $branch->getAddress(),
            'email'=> $branch->getEmail(),
            'start_date'=> $branch->getStart_date(),
            'serie'=> $branch->getSerie(),
            'status'=> $branch->getStatus()
        ]);
    }

}

