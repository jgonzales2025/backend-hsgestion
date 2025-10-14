<?php

namespace App\Modules\SubCategory\Application\UseCases;

use App\Modules\SubCategory\Domain\Interfaces\SubCategoryRepositoryInterface;

class FindAllSubCategoriesUseCase
{
    private subCategoryRepositoryInterface $subCategoryRepository;

    public function __construct(subCategoryRepositoryInterface $subCategoryRepository)
    {
        $this->subCategoryRepository = $subCategoryRepository;
    }

    public function execute(): array
    {
        return $this->subCategoryRepository->findAll();
    }
}
