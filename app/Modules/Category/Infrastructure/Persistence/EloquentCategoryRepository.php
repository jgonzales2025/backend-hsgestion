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

        return $categories->map(fn ($eloquentCategory) => $this->mapToEntity($eloquentCategory))->toArray();
    }

    public function save(Category $category): Category
    {
        $eloquentCategory = EloquentCategory::create([
            'name' => $category->getName(),
            'status' => $category->getStatus(),
        ]);

        return $this->mapToEntity($eloquentCategory);
    }

    public function findById($id): ?Category
    {
        $eloquentCategory = EloquentCategory::find($id);

        if (!$eloquentCategory) {
            return null;
        }

        return $this->mapToEntity($eloquentCategory);
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

        return $this->mapToEntity($eloquentCategory);
    }

    private function mapToEntity($eloquentCategory): Category
    {
        return new Category(
            id: $eloquentCategory->id,
            name: $eloquentCategory->name,
            status: $eloquentCategory->status,
        );
    }
}
