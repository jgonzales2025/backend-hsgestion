<?php

namespace App\Modules\SubCategory\Application\UseCases;

use App\Modules\SubCategory\Application\DTOs\SubCategoryDTO;
use App\Modules\SubCategory\Domain\Entities\SubCategory;
use App\Modules\SubCategory\Domain\Interfaces\SubCategoryRepositoryInterface;

class UpdateSubCategoryUseCase
{
    private subCategoryRepositoryInterface $subCategoryRepository;

    public function __construct(subCategoryRepositoryInterface $subCategoryRepository)
    {
        $this->subCategoryRepository = $subCategoryRepository;
    }

    public function execute($id, SubCategoryDTO $subCategoryDTO)
    {
        $subCategory = new SubCategory(
            id: $id,
            name: $subCategoryDTO->name,
            category_id: $subCategoryDTO->category_id,
            category_name: null,
            status: $subCategoryDTO->status,
        );

        return $this->subCategoryRepository->update($subCategory);
    }
}
