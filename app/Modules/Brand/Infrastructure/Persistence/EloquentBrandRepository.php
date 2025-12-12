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
            'name' => $brand->getName()
        ]);
        $eloquentBrand->refresh();

        return new Brand(
            id: $eloquentBrand->id,
            name: $eloquentBrand->name,
            status: $eloquentBrand->status,
        );
    }

    public function findAllPaginateInfinite(?string $name)
    {
        return EloquentBrand::query()
            ->when($name, function ($query) use ($name) {
                return $query->where('name', 'like', "%{$name}%");
            })
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->cursorPaginate(10);
    }

    public function findAll(?string $name, ?int $status)
    {
        $brands = EloquentBrand::query()
            ->when($name, function ($query) use ($name) {
                return $query->where('name', 'like', "%{$name}%");
            })
            ->when($status !== null, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $brands->getCollection()->transform(function ($brand) {
            return new Brand(
                id: $brand->id,
                name: $brand->name,
                status: $brand->status,
            );
        });

        return $brands;
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
            return null;
        }

        $eloquentBrand->update([
            'name' => $brand->getName()
        ]);

        return new Brand(
            id: $eloquentBrand->id,
            name: $eloquentBrand->name,
            status: $eloquentBrand->status,
        );
    }

    public function updateStatus(int $brandId, int $status): void
    {
        EloquentBrand::where('id', $brandId)->update(['status' => $status]);
    }


}
