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
            status: $branch->status,
             phones: $branch->phones ? $branch->phones->pluck('phone')->toArray() : []
        ))->toArray();
    }

  public function findById(int $id): ?Branch
{
    $branch = EloquentBranch::with('company','phones')->find($id);
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
        status: $branch->status,
        phones: $branch->phones ? $branch->phones->pluck('phone')->toArray() : []
    );
}

public function findByCiaId(int $cia_id): array
{
    $branches = EloquentBranch::with('company', 'phones') // 
        ->where('cia_id', $cia_id)
        ->get();

    if ($branches->isEmpty()) {
        return [];
    }

    return $branches->map(function ($branch) {
        return new Branch(
            id: $branch->id,
            cia_id: $branch->cia_id,
            name: $branch->name,
            address: $branch->address,
            email: $branch->email,
            start_date: $branch->start_date,
            serie: $branch->serie,
            status: $branch->status,
            phones: $branch->phones ? $branch->phones->pluck('phone')->toArray() : []
        );
    })->all();
}

public function update(Branch $branch): void
{
    $eloquentBranch = EloquentBranch::find($branch->getId());

    if (!$eloquentBranch) {
        throw new \Exception("Branch no encontrado");
    }

    // Actualiza los datos principales
    $eloquentBranch->update([
        'cia_id'     => $branch->getCia_id() ?? $eloquentBranch->cia_id,
        'name'       => $branch->getName(),
        'address'    => $branch->getAddress(),
        'email'      => $branch->getEmail(),
        'start_date' => $branch->getStart_date(),
        'serie'      => $branch->getSerie(),
        'status'     => $branch->getStatus() ?? $eloquentBranch->status,
    ]);
    

    if (!empty($branch->getPhones())) {
        // Borra los teléfonos anteriores
        $eloquentBranch->phones()->delete();

        

        // Inserta los nuevos
        foreach ($branch->getPhones() as $phone) {
            if (!empty($phone)) {
                $eloquentBranch->phones()->create(['phone' => $phone]);
            }
        }
    }
}


}

