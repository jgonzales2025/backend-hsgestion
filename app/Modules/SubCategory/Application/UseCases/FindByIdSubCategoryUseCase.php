<?php

namespace App\Modules\SubCategory\Application\UseCases;

use App\Modules\SubCategory\Domain\Entities\SubCategory;
use App\Modules\SubCategory\Domain\Interfaces\SubCategoryRepositoryInterface;

class FindByIdSubCategoryUseCase
{
    public function __construct(private readonly subCategoryRepositoryInterface $subCategoryRepository)
    { 
    }

    public function execute(int $id): SubCategory
    {
        return $this->subCategoryRepository->findById($id);
    }
}
