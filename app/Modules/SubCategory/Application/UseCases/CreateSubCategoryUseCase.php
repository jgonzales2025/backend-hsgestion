<?php

namespace App\Modules\SubCategory\Application\UseCases;

use App\Modules\SubCategory\Application\DTOs\SubCategoryDTO;
use App\Modules\SubCategory\Domain\Entities\SubCategory;
use App\Modules\SubCategory\Domain\Interfaces\SubCategoryRepositoryInterface;

class CreateSubCategoryUseCase
{
    private subCategoryRepositoryInterface $subCategoryRepository;

    public function __construct(subCategoryRepositoryInterface $subCategoryRepository)
    {
        $this->subCategoryRepository = $subCategoryRepository;
    }

    public function execute(SubCategoryDTO $subCategoryDTO): SubCategory
    {
        $subCategory = new SubCategory(
            id: 0,
            name: $subCategoryDTO->name,
            category_id: $subCategoryDTO->category_id,
            category_name: null,
            status: $subCategoryDTO->status,
        );

        return $this->subCategoryRepository->save($subCategory);
    }
}
