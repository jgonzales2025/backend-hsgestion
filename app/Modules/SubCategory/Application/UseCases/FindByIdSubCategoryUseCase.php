<?php

namespace App\Modules\SubCategory\Application\UseCases;

use App\Modules\SubCategory\Domain\Entities\SubCategory;
use App\Modules\SubCategory\Domain\Interfaces\SubCategoryRepositoryInterface;

class FindByIdSubCategoryUseCase
{
    private subCategoryRepositoryInterface $subCategoryRepository;

    public function __construct(subCategoryRepositoryInterface $subCategoryRepository)
    {
        $this->subCategoryRepository = $subCategoryRepository;
    }

    public function execute(int $id): SubCategory
    {
        return $this->subCategoryRepository->findById($id);
    }
}
