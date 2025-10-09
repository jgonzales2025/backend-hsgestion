<?php

namespace App\Modules\Category\Infrastructure\Controllers;

use App\Modules\Category\Application\DTOs\CategoryDTO;
use App\Modules\Category\Application\UseCases\CreateCategoryUseCase;
use App\Modules\Category\Application\UseCases\FindAllCategoriesUseCase;
use App\Modules\Category\Application\UseCases\FindByIdCategoryUseCase;
use App\Modules\Category\Application\UseCases\UpdateCategoryUseCase;
use App\Modules\Category\Domain\Interfaces\CategoryRepositoryInterface;
use App\Modules\Category\Infrastructure\Requests\StoreCategoryRequest;
use App\Modules\Category\Infrastructure\Requests\UpdateCategoryRequest;
use App\Modules\Category\Infrastructure\Resources\CategoryResource;
use Illuminate\Http\JsonResponse;

class CategoryController
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index(): array
    {
        $categoriesUseCases = new FindAllCategoriesUseCase($this->categoryRepository);
        $categories = $categoriesUseCases->execute();

        return CategoryResource::collection($categories)->resolve();
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $categoryDTO = new CategoryDTO($request->validated());
        $categoryUseCase = new CreateCategoryUseCase($this->categoryRepository);
        $category = $categoryUseCase->execute($categoryDTO);

        return response()->json(
            (new CategoryResource($category))->resolve(),
             201
        );
    }

    public function show($id): JsonResponse
    {
        $categoryUseCase = new FindByIdCategoryUseCase($this->categoryRepository);
        $category = $categoryUseCase->execute($id);

        return response()->json(
            (new CategoryResource($category))->resolve(),
             200
        );
    }

    public function update(UpdateCategoryRequest $request, $id): JsonResponse
    {
        $categoryDTO = new CategoryDTO($request->validated());
        $categoryUseCase = new UpdateCategoryUseCase($this->categoryRepository);
        $categoryUpdate = $categoryUseCase->execute($id, $categoryDTO);

        return response()->json(
            (new CategoryResource($categoryUpdate))->resolve(),
              200
        );
    }
}
