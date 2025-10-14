<?php

namespace App\Modules\Category\Application\UseCases;

use App\Modules\Category\Domain\Interfaces\CategoryRepositoryInterface;

class FindAllCategoriesUseCase
{
    private categoryRepositoryInterface $categoryRepository;

    public function __construct(categoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function execute(): array
    {
        return $this->categoryRepository->findAll();
    }
}
