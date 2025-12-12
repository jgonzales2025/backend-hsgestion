<?php

namespace App\Modules\SubCategory\Infrastructure\Persistence;

use App\Modules\SubCategory\Domain\Entities\SubCategory;
use App\Modules\SubCategory\Domain\Interfaces\SubCategoryRepositoryInterface;
use App\Modules\SubCategory\Infrastructure\Models\EloquentSubCategory;

class EloquentSubCategoryRepository implements SubCategoryRepositoryInterface
{

    public function findAllPaginateInfinite(?string $description, ?int $category_id)
    {
        return EloquentSubCategory::query()
            ->with('category') // Cargar la relación para tener category_name disponible
            ->where('status', 1)
            ->when($description, fn($query) => $query->where('name', 'like', "%{$description}%"))
            ->when($category_id, fn($query) => $query->where('category_id', $category_id))
            ->orderBy('id', 'asc') // Cursor pagination requiere ordenar por columna única
            ->cursorPaginate(10);
    }

    public function findAll(?string $description, ?int $category_id, ?int $status)
    {
        $subCategories = EloquentSubCategory::with('category')
            ->when($description, fn($query) => $query->where('name', 'like', "%{$description}%")
                ->orWhereHas('category', fn($query) => $query->where('name', 'like', "%{$description}%")))
            ->when($category_id !== null, fn($query) => $query->where('category_id', $category_id))
            ->when($status !== null, fn($query) => $query->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $subCategories->getCollection()->transform(fn($subCategory) => new SubCategory(
            id: $subCategory->id,
            name: $subCategory->name,
            category_id: $subCategory->category_id,
            category_name: $subCategory->category->name,
            status: $subCategory->status
        ));
        return $subCategories;
    }

    public function save(SubCategory $subCategory): SubCategory
    {
        $eloquentSubCategory = EloquentSubCategory::create([
            'name' => $subCategory->getName(),
            'category_id' => $subCategory->getCategoryId()
        ]);
        $eloquentSubCategory->refresh();

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
            return null;
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

        $eloquentSubCategory->update([
            'name' => $subCategory->getName(),
            'category_id' => $subCategory->getCategoryId()
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

    public function updateStatus(int $subCategoryId, int $status): void
    {
        EloquentSubCategory::where('id', $subCategoryId)->update(['status' => $status]);
    }
}
