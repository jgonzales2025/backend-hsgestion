<?php

namespace App\Modules\SubCategory\Infrastructure\Persistence;

use App\Modules\SubCategory\Domain\Entities\SubCategory;
use App\Modules\SubCategory\Domain\Interfaces\SubCategoryRepositoryInterface;
use App\Modules\SubCategory\Infrastructure\Models\EloquentSubCategory;

class EloquentSubCategoryRepository implements SubCategoryRepositoryInterface
{

    public function findAll(): array
    {
        $subCategories = EloquentSubCategory::with('category')->get();

        return $subCategories->map(function ($subCategory) {
            return new SubCategory(
                id: $subCategory->id,
                name: $subCategory->name,
                category_id: $subCategory->category_id,
                category_name: $subCategory->category->name,
                status: $subCategory->status
            );
        })->toArray();
    }

    public function save(SubCategory $subCategory): SubCategory
    {
        $eloquentSubCategory = EloquentSubCategory::create([
            'name' => $subCategory->getName(),
            'category_id' => $subCategory->getCategoryId(),
            'status' => $subCategory->getStatus(),
        ]);

        return new SubCategory(
            id: $eloquentSubCategory->id,
            name: $eloquentSubCategory->name,
            category_id: $eloquentSubCategory->category_id,
            category_name: $eloquentSubCategory->category->name,
            status: $eloquentSubCategory->status,
        );
    }

    public function findById(int $id): ?SubCategory
    {
        $eloquentSubCategory = EloquentSubCategory::with('category')->find($id);

        if (!$eloquentSubCategory) {
            throw new \Exception("Subcategoria no encontrada");
        }

        return new SubCategory(
            id: $id,
            name: $eloquentSubCategory->name,
            category_id: $eloquentSubCategory->category_id,
            category_name: $eloquentSubCategory->category->name,
            status: $eloquentSubCategory->status
        );
    }

    public function update(SubCategory $subCategory): ?SubCategory
    {
        $eloquentSubCategory = EloquentSubCategory::find($subCategory->getId());

        if (!$eloquentSubCategory) {
            throw new \Exception("Subcategoria no encontrada");
        }

        $eloquentSubCategory->update([
            'name' => $subCategory->getName(),
            'category_id' => $subCategory->getCategoryId(),
            'status' => $subCategory->getStatus(),
        ]);

        return new SubCategory(
            id: $eloquentSubCategory->id,
            name: $eloquentSubCategory->name,
            category_id: $eloquentSubCategory->category_id,
            category_name: $eloquentSubCategory->category->name,
            status: $eloquentSubCategory->status,
        );
    }

    public function findByCategoryId(int $categoryId): array
    {
        $subCategories = EloquentSubCategory::with('category')
            ->where('category_id', $categoryId)
            ->get();

        return $subCategories->map(function ($subCategory) {
            return new SubCategory(
                id: $subCategory->id,
                name: $subCategory->name,
                category_id: $subCategory->category_id,
                category_name: $subCategory->category->name,
                status: $subCategory->status
            );
        })->toArray();
    }
}
