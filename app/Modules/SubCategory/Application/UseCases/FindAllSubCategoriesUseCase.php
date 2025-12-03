<?php

namespace App\Modules\SubCategory\Application\UseCases;

use App\Modules\SubCategory\Domain\Interfaces\SubCategoryRepositoryInterface;

class FindAllSubCategoriesUseCase
{
    public function __construct(private readonly subCategoryRepositoryInterface $subCategoryRepository)
    { 
    }

    public function execute(?string $description, ?int $category_id, ?int $status)
    {
        return $this->subCategoryRepository->findAll($description, $category_id, $status);
    }
}
