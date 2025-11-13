<?php

namespace App\Modules\Category\Application\UseCases;

use App\Modules\Category\Application\DTOs\CategoryDTO;
use App\Modules\Category\Domain\Entities\Category;
use App\Modules\Category\Domain\Interfaces\CategoryRepositoryInterface;

class UpdateCategoryUseCase
{
    private categoryRepositoryInterface $categoryRepository;

    public function __construct(categoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function execute($id, CategoryDTO $categoryDTO): Category
    {
        $category = new Category(
            id: $id,
            name: $categoryDTO->name
        );

        return $this->categoryRepository->update($category);
    }
}
