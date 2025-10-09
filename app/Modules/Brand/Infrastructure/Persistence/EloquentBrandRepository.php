<?php

namespace App\Modules\Brand\Infrastructure\Persistence;

use App\Modules\Brand\Domain\Entities\Brand;
use App\Modules\Brand\Domain\Interfaces\BrandRepositoryInterface;
use App\Modules\Brand\Infrastructure\Models\EloquentBrand;

class EloquentBrandRepository implements BrandRepositoryInterface
{

    public function save(Brand $brand): ?Brand
    {
        $eloquentBrand = EloquentBrand::create([
            'name' => $brand->getName(),
            'status' => $brand->getStatus()
        ]);

        return new Brand(
            id: $eloquentBrand->id,
            name: $eloquentBrand->name,
            status: $eloquentBrand->status,
        );
    }

    public function findAll(): array
    {
        $brands = EloquentBrand::all()->sortByDesc('created_at');

        if ($brands->isEmpty()) {
            return [];
        }

        return $brands->map(function ($brand) {
            return new Brand(
                id: $brand->id,
                name: $brand->name,
                status: $brand->status,
            );
        })->toArray();
    }

    public function findById(int $id): ?Brand
    {
        $brand = EloquentBrand::find($id);

        if (!$brand) {
            return null;
        }

        return new Brand(
            id: $brand->id,
            name: $brand->name,
            status: $brand->status,
        );
    }

    public function update(Brand $brand): ?Brand
    {
        $eloquentBrand = EloquentBrand::find($brand->getId());

        if (!$eloquentBrand) {
            throw new \Exception("Marca no encontrada");
        }

        $eloquentBrand->update([
            'name' => $brand->getName(),
            'status' => $brand->getStatus()
        ]);

        return new Brand(
            id: $eloquentBrand->id,
            name: $eloquentBrand->name,
            status: $eloquentBrand->status,
        );
    }


}
