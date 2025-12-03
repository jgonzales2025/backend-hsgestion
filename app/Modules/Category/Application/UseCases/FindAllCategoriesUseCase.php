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

    public function execute(?string $description, ?int $status)
    {
        return $this->categoryRepository->findAll($description, $status);
    }
}
