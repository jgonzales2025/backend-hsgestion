<?php

namespace App\Modules\SubCategory\Application\UseCases;

use App\Modules\SubCategory\Domain\Interfaces\SubCategoryRepositoryInterface;

class FindAllSubCategoriesUseCase
{
    public function __construct(private readonly subCategoryRepositoryInterface $subCategoryRepository)
    { 
    }

    public function execute(): array
    {
        return $this->subCategoryRepository->findAll();
    }
}
