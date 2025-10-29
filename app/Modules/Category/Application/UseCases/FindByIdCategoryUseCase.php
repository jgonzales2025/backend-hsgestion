<?php

namespace App\Modules\Category\Application\UseCases;

use App\Modules\Category\Domain\Entities\Category;
use App\Modules\Category\Domain\Interfaces\CategoryRepositoryInterface;

class FindByIdCategoryUseCase
{
    private categoryRepositoryInterface $categoryRepository;

    public function __construct(categoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function execute(int $id): ?Category
    {
        return $this->categoryRepository->findById($id);
    }
}
