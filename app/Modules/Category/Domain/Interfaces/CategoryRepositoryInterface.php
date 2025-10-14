<?php

namespace App\Modules\Category\Domain\Interfaces;

use App\Modules\Category\Domain\Entities\Category;

interface CategoryRepositoryInterface
{
    public function findAll(): array;

    public function save(Category $category): ?Category;

    public function findById(int $id): ?Category;

    public function update(Category $category): ?Category;
}
