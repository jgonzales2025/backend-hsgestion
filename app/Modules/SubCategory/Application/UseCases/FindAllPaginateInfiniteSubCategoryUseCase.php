<?php

namespace App\Modules\SubCategory\Application\UseCases;

use App\Modules\SubCategory\Domain\Interfaces\SubCategoryRepositoryInterface;

class FindAllPaginateInfiniteSubCategoryUseCase
{
    public function __construct(private readonly SubCategoryRepositoryInterface $subCategoryRepository){}

    public function execute(?string $name, ?int $category_id)
    {
        return $this->subCategoryRepository->findAllPaginateInfinite($name, $category_id);
    }
}