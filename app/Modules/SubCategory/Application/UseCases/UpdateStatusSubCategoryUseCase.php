<?php

namespace App\Modules\SubCategory\Application\UseCases;

use App\Modules\SubCategory\Domain\Interfaces\SubCategoryRepositoryInterface;

class UpdateStatusSubCategoryUseCase
{
    private SubCategoryRepositoryInterface $subCategoryRepository;

    public function __construct(SubCategoryRepositoryInterface $subCategoryRepository)
    {
        $this->subCategoryRepository = $subCategoryRepository;
    }

    public function execute(int $subCategoryId, int $status): void
    {
        $this->subCategoryRepository->updateStatus($subCategoryId, $status);
    }
}
