<?php

namespace App\Modules\SubCategory\Application\UseCases;

use App\Modules\SubCategory\Domain\Interfaces\SubCategoryRepositoryInterface;

class FindByCategoryIdUseCase
{
    public function __construct(private readonly SubCategoryRepositoryInterface $subCategoryRepository)
    {
    }

    public function execute($id): array
    {
        return $this->subCategoryRepository->findByCategoryId($id);
    }
}
