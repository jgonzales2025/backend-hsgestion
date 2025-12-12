<?php

namespace App\Modules\Category\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Category\Application\DTOs\CategoryDTO;
use App\Modules\Category\Application\UseCases\CreateCategoryUseCase;
use App\Modules\Category\Application\UseCases\FindAllCategoriesUseCase;
use App\Modules\Category\Application\UseCases\FindByIdCategoryUseCase;
use App\Modules\Category\Application\UseCases\UpdateCategoryUseCase;
use App\Modules\Category\Application\UseCases\UpdateStatusCategoryUseCase;
use App\Modules\Category\Domain\Interfaces\CategoryRepositoryInterface;
use App\Modules\Category\Infrastructure\Requests\StoreCategoryRequest;
use App\Modules\Category\Infrastructure\Requests\UpdateCategoryRequest;
use App\Modules\Category\Infrastructure\Resources\CategoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(private readonly CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function indexPaginateInfinite(Request $request): JsonResponse
    {
        $description = $request->query('description');
        $categoriesUseCase = new \App\Modules\Category\Application\UseCases\FindAllPaginateInfiniteUseCase($this->categoryRepository);
        $categories = $categoriesUseCase->execute($description);

        return new JsonResponse([
            'data' => CategoryResource::collection($categories)->resolve(),
            'next_cursor' => $categories->nextCursor()?->encode(),
            'prev_cursor' => $categories->previousCursor()?->encode(),
            'next_page_url' => $categories->nextPageUrl(),
            'prev_page_url' => $categories->previousPageUrl(),
            'per_page' => $categories->perPage(),
            'path' => $categories->path(),
        ]);
    }
    public function indexPaginate(Request $request): JsonResponse
    {
        $descripton = $request->query('description');
        $status = $request->query('status');
        $categoriesUseCases = new FindAllCategoriesUseCase($this->categoryRepository);
        $categories = $categoriesUseCases->execute($descripton, $status);

        return new JsonResponse([
            'data' => CategoryResource::collection($categories)->resolve(),
            'current_page' => $categories->currentPage(),
            'per_page' => $categories->perPage(),
            'total' => $categories->total(),
            'last_page' => $categories->lastPage(),
            'next_page_url' => $categories->nextPageUrl(),
            'prev_page_url' => $categories->previousPageUrl(),
            'first_page_url' => $categories->url(1),
            'last_page_url' => $categories->url($categories->lastPage()),
        ]);
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

        if (!$category) {
            return response()->json(["message" => "Categoría no encontrada"]);
        }

        return response()->json(
            (new CategoryResource($category))->resolve(),
            200
        );
    }

    public function update(UpdateCategoryRequest $request, $id): JsonResponse
    {
        $categoryUseCase = new FindByIdCategoryUseCase($this->categoryRepository);
        $category = $categoryUseCase->execute($id);

        if (!$category) {
            return response()->json(["message" => "Categoría no encontrada"]);
        }

        $categoryDTO = new CategoryDTO($request->validated());
        $categoryUpdateUseCase = new UpdateCategoryUseCase($this->categoryRepository);
        $categoryUpdate = $categoryUpdateUseCase->execute($id, $categoryDTO);

        return response()->json(
            (new CategoryResource($categoryUpdate))->resolve(),
            200
        );
    }

    public function updateStatus(int $id, Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'status' => 'required|integer|in:0,1',
        ]);

        $status = $validatedData['status'];
        $updateStatusCategoryUseCase = new UpdateStatusCategoryUseCase($this->categoryRepository);
        $updateStatusCategoryUseCase->execute($id, $status);

        return response()->json(["message" => "Estado actualizado correctamente"], 200);
    }
}
