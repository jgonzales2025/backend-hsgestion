<?php

namespace App\Modules\SubCategory\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\SubCategory\Application\DTOs\SubCategoryDTO;
use App\Modules\SubCategory\Application\UseCases\CreateSubCategoryUseCase;
use App\Modules\SubCategory\Application\UseCases\FindAllSubCategoriesUseCase;
use App\Modules\SubCategory\Application\UseCases\FindByCategoryIdUseCase;
use App\Modules\SubCategory\Application\UseCases\FindByIdSubCategoryUseCase;
use App\Modules\SubCategory\Application\UseCases\UpdateStatusSubCategoryUseCase;
use App\Modules\SubCategory\Application\UseCases\UpdateSubCategoryUseCase;
use App\Modules\SubCategory\Domain\Interfaces\SubCategoryRepositoryInterface;
use App\Modules\SubCategory\Infrastructure\Requests\StoreSubCategoryRequest;
use App\Modules\SubCategory\Infrastructure\Requests\UpdateSubCategoryRequest;
use App\Modules\SubCategory\Infrastructure\Resources\SubCategoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubCategoryController extends Controller
{
    protected $subCategoryRepository;

    public function __construct(SubCategoryRepositoryInterface $subCategoryRepository)
    {
        $this->subCategoryRepository = $subCategoryRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $description = $request->query('description');
        $category_id = $request->query('category_id');
        $status = $request->query('status') !== null ? (int) $request->query('status') : null;
        $subCategoriesUseCases = new FindAllSubCategoriesUseCase($this->subCategoryRepository);
        $subCategories = $subCategoriesUseCases->execute($description, $category_id, $status);

        return new JsonResponse([
            'data' => SubCategoryResource::collection($subCategories)->resolve(),
            'current_page' => $subCategories->currentPage(),
            'per_page' => $subCategories->perPage(),
            'total' => $subCategories->total(),
            'last_page' => $subCategories->lastPage(),
            'next_page_url' => $subCategories->nextPageUrl(),
            'prev_page_url' => $subCategories->previousPageUrl(),
            'first_page_url' => $subCategories->url(1),
            'last_page_url' => $subCategories->url($subCategories->lastPage()),
        ]);
    }

    public function store(StoreSubCategoryRequest $request): JsonResponse
    {
        $subCategoryDTO = new SubCategoryDTO($request->validated());
        $subCategoryUseCase = new CreateSubCategoryUseCase($this->subCategoryRepository);
        $subCategory = $subCategoryUseCase->execute($subCategoryDTO);

        return response()->json(
            (new SubCategoryResource($subCategory))->resolve(),
            201
        );
    }

    public function show($id): JsonResponse
    {
        $subCategoryUseCase = new FindByIdSubCategoryUseCase($this->subCategoryRepository);
        $subCategory = $subCategoryUseCase->execute($id);

        if (!$subCategory) {
            return response()->json(['message' => 'Subcategoria no encontrada'],404);
        }

        return response()->json(
            (new SubCategoryResource($subCategory))->resolve(),
            200
        );
    }

    public function update(UpdateSubCategoryRequest $request, $id): JsonResponse
    {
        $subCategoryUseCase = new FindByIdSubCategoryUseCase($this->subCategoryRepository);
        $subCategory = $subCategoryUseCase->execute($id);

        if (!$subCategory) {
            return response()->json(['message' => 'Subcategoria no encontrada'],404);
        }

        $subCategoryDTO = new SubCategoryDTO($request->validated());
        $subCategoryUpdateUseCase = new UpdateSubCategoryUseCase($this->subCategoryRepository);
        $subCategory = $subCategoryUpdateUseCase->execute($id, $subCategoryDTO);

        return response()->json(
            (new SubCategoryResource($subCategory))->resolve(),
            200
        );
    }

    public function findByCategoryid($id): array
    {
        $subCategoryUseCase = new FindByCategoryIdUseCase($this->subCategoryRepository);
        $subCategories = $subCategoryUseCase->execute($id);

        return SubCategoryResource::collection($subCategories)->resolve();
    }

    public function updateStatus(int $id, Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'status' => 'required|integer|in:0,1',
        ]);
        $status = $validatedData['status'];

        $subCategoryUseCase = new UpdateStatusSubCategoryUseCase($this->subCategoryRepository);
        $subCategoryUseCase->execute($id, $status);

        return response()->json(
            ['message' => 'Estado actualizado correctamente'],
            200
        );
    }
}
