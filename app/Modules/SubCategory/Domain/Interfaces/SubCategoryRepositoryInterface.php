<?php

namespace App\Modules\SubCategory\Domain\Interfaces;

use App\Modules\SubCategory\Domain\Entities\SubCategory;

interface SubCategoryRepositoryInterface
{
    public function findAll(): array;

    public function save(SubCategory $subCategory): ?SubCategory;

    public function findById(int $id): ?SubCategory;

    public function update(SubCategory $subCategory): ?SubCategory;
}
