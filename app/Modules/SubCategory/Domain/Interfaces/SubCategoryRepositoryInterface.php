<?php

namespace App\Modules\SubCategory\Domain\Interfaces;

use App\Modules\SubCategory\Domain\Entities\SubCategory;

interface SubCategoryRepositoryInterface
{
    public function findAll(?string $description, ?int $category_id, ?int $status);

    public function save(SubCategory $subCategory): ?SubCategory;

    public function findById(int $id): ?SubCategory;

    public function update(SubCategory $subCategory): ?SubCategory;

    public function findByCategoryId(int $categoryId): array;

    public function updateStatus(int $subCategoryId, int $status): void;
}
