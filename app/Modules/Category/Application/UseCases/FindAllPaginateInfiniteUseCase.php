<?php

namespace App\Modules\Category\Application\UseCases;

use App\Modules\Category\Domain\Interfaces\CategoryRepositoryInterface;

class FindAllPaginateInfiniteUseCase
{
    public function __construct(private readonly CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function execute(?string $description)
    {
        return $this->categoryRepository->findAllPaginateInfinite($description);
    }
}