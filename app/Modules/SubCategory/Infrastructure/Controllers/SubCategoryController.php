<?php

namespace App\Modules\SubCategory\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\SubCategory\Application\DTOs\SubCategoryDTO;
use App\Modules\SubCategory\Application\UseCases\CreateSubCategoryUseCase;
use App\Modules\SubCategory\Application\UseCases\FindAllSubCategoriesUseCase;
use App\Modules\SubCategory\Application\UseCases\FindByCategoryIdUseCase;
use App\Modules\SubCategory\Application\UseCases\FindByIdSubCategoryUseCase;
use App\Modules\SubCategory\Application\UseCases\UpdateSubCategoryUseCase;
use App\Modules\SubCategory\Domain\Interfaces\SubCategoryRepositoryInterface;
use App\Modules\SubCategory\Infrastructure\Requests\StoreSubCategoryRequest;
use App\Modules\SubCategory\Infrastructure\Requests\UpdateSubCategoryRequest;
use App\Modules\SubCategory\Infrastructure\Resources\SubCategoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SubCategoryController extends Controller
{
    protected $subCategoryRepository;

    public function __construct(SubCategoryRepositoryInterface $subCategoryRepository)
    {
        $this->subCategoryRepository = $subCategoryRepository;
    }

    public function index(): array
    {
        $subCategoriesUseCases = new FindAllSubCategoriesUseCase($this->subCategoryRepository);
        $subCategories = $subCategoriesUseCases->execute();

        return SubCategoryResource::collection($subCategories)->resolve();
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

        return response()->json(
            (new SubCategoryResource($subCategory))->resolve(),
             200
        );
    }

    public function update(UpdateSubCategoryRequest $request, $id): JsonResponse
    {
        $subCategoryDTO = new SubCategoryDTO($request->validated());
        $subCategoryUseCase = new UpdateSubCategoryUseCase($this->subCategoryRepository);
        $subCategory = $subCategoryUseCase->execute($id, $subCategoryDTO);

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
}
