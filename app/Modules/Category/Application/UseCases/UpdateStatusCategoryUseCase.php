<?php

namespace App\Modules\Category\Application\UseCases;

use App\Modules\Category\Domain\Interfaces\CategoryRepositoryInterface;

class UpdateStatusCategoryUseCase
{
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function execute(int $categoryId, int $status): void
    {
        $this->categoryRepository->updateStatus($categoryId, $status);
    }
}
