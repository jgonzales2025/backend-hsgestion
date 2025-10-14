<?php

namespace App\Modules\Category\Infrastructure\Persistence;

use App\Modules\Category\Domain\Entities\Category;
use App\Modules\Category\Domain\Interfaces\CategoryRepositoryInterface;
use App\Modules\Category\Infrastructure\Models\EloquentCategory;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{

    public function findAll(): array
    {
        $categories = EloquentCategory::all()->sortByDesc('created_at');

        if ($categories->isEmpty()) {
            return [];
        }

        return $categories->map(function ($category) {
            return new Category(
                id: $category->id,
                name: $category->name,
                status: $category->status,
            );
        })->toArray();
    }

    public function save(Category $category): Category
    {
        $eloquentCategory = EloquentCategory::create([
            'name' => $category->getName(),
            'status' => $category->getStatus(),
        ]);

        return new Category(
            id: $eloquentCategory->id,
            name: $eloquentCategory->name,
            status: $eloquentCategory->status,
        );
    }

    public function findById($id): Category
    {
        $eloquentCategory = EloquentCategory::find($id);

        if (!$eloquentCategory) {
            throw new \Exception("Categoria no encontrada");
        }

        return new Category(
            id: $eloquentCategory->id,
            name: $eloquentCategory->name,
            status: $eloquentCategory->status,
        );
    }

    public function update(Category $category): ?Category
    {
        $eloquentCategory = EloquentCategory::find($category->getId());

        if (!$eloquentCategory) {
            throw new \Exception("Categoria no encontrada");
        }

        $eloquentCategory->update([
            'name' => $category->getName(),
            'status' => $category->getStatus(),
        ]);

        return new Category(
            id: $eloquentCategory->id,
            name: $eloquentCategory->name,
            status: $eloquentCategory->status,
        );
    }
}
