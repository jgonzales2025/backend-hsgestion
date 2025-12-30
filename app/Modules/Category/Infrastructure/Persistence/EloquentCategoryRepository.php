<?php

namespace App\Modules\Category\Infrastructure\Persistence;

use App\Modules\Category\Domain\Entities\Category;
use App\Modules\Category\Domain\Interfaces\CategoryRepositoryInterface;
use App\Modules\Category\Infrastructure\Models\EloquentCategory;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{

    public function findAllPaginateInfinite(?string $description)
    {
        // Para cursor pagination, devolvemos los modelos Eloquent directamente
        // porque cursorPaginate necesita acceder a las propiedades del modelo
        // La transformación se hará en el controller con CategoryResource
        return EloquentCategory::query()
            ->where('st_concept', 0)
            ->where('status', 1) // Solo categorías activas para el select
            ->when($description, fn($query) => $query->where('name', 'like', "%{$description}%"))
            ->orderBy('name', 'asc') // Cursor pagination requiere ordenar por columna única
            ->cursorPaginate(10); // Cursor pagination es más eficiente para infinite scroll
    }

    public function findAll(?string $description, ?int $status)
    {
        $categories = EloquentCategory::query()
            ->where('st_concept', 0)
            ->when($description, fn($query) => $query->where('name', 'like', "%{$description}%"))
            ->when($status !== null, fn($query) => $query->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories->getCollection()->transform(fn($eloquentCategory) => $this->mapToEntity($eloquentCategory));
        return $categories;
    }

    public function save(Category $category): Category
    {
        $eloquentCategory = EloquentCategory::create([
            'name' => $category->getName()
        ]);
        $eloquentCategory->refresh();

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
            return null;
        }

        $eloquentCategory->update([
            'name' => $category->getName()
        ]);

        return $this->mapToEntity($eloquentCategory);
    }

    public function updateStatus(int $categoryId, int $status): void
    {
        EloquentCategory::where('id', $categoryId)->update(['status' => $status]);
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
