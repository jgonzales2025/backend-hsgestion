<?php

namespace App\Modules\Brand\Infrastructure\Controllers;

use App\Modules\Brand\Application\DTOs\BrandDTO;
use App\Modules\Brand\Application\UseCases\CreateBrandUseCase;
use App\Modules\Brand\Application\UseCases\FindAllBrandUseCases;
use App\Modules\Brand\Application\UseCases\FindAllPaginateInfiniteBrandUseCase;
use App\Modules\Brand\Application\UseCases\FindByIdBrandUseCase;
use App\Modules\Brand\Application\UseCases\UpdateBrandUseCase;
use App\Modules\Brand\Application\UseCases\UpdateStatusBrand;
use App\Modules\Brand\Domain\Interfaces\BrandRepositoryInterface;
use App\Modules\Brand\Infrastructure\Persistence\EloquentBrandRepository;
use App\Modules\Brand\Infrastructure\Requests\StoreBrandRequest;
use App\Modules\Brand\Infrastructure\Requests\UpdateBrandRequest;
use App\Modules\Brand\Infrastructure\Resources\BrandResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

readonly class BrandController
{
    public function __construct(private readonly BrandRepositoryInterface $brandRepository){}


    public function indexPaginateInfinite(Request $request): JsonResponse
    {
        $name = $request->query('description');
        $brandUseCase = new FindAllPaginateInfiniteBrandUseCase($this->brandRepository);
        $brands = $brandUseCase->execute($name);

        return new JsonResponse([
            'data' => BrandResource::collection($brands)->resolve(),
            'next_cursor' => $brands->nextCursor()?->encode(),
            'prev_cursor' => $brands->previousCursor()?->encode(),
            'next_page_url' => $brands->nextPageUrl(),
            'prev_page_url' => $brands->previousPageUrl(),
            'per_page' => $brands->perPage()
        ]);
    }
    public function index(Request $request): array|JsonResponse
    {
        $name = $request->query('name');
        $status = $request->query('status') !== null ? (int) $request->query('status') : null;

        $brandUseCase = new FindAllBrandUseCases($this->brandRepository);
        $brands = $brandUseCase->execute($name, $status);
        return new JsonResponse([
            'data' => BrandResource::collection($brands)->resolve(),
            'current_page' => $brands->currentPage(),
            'per_page' => $brands->perPage(),
            'total' => $brands->total(),
            'last_page' => $brands->lastPage(),
            'next_page_url' => $brands->nextPageUrl(),
            'prev_page_url' => $brands->previousPageUrl(),
            'first_page_url' => $brands->url(1),
            'last_page_url' => $brands->url($brands->lastPage()),
        ]);
    }

    public function store(StoreBrandRequest $request)
    {
        $brandDTO = new BrandDTO($request->validated());
        $brandUseCase = new CreateBrandUseCase($this->brandRepository);
        $brand = $brandUseCase->execute($brandDTO);

        return response()->json(
            (new BrandResource($brand))->resolve(),
            201
        );
    }

    public function show($id): JsonResponse
    {
        $brandUseCase = new FindByIdBrandUseCase($this->brandRepository);
        $brand = $brandUseCase->execute($id);

        return response()->json(
            (new BrandResource($brand))->resolve(),
            200
        );
    }

    public function update(UpdateBrandRequest $request, $id): JsonResponse
    {
        $userDTO = new BrandDTO([
            'id' => $id,
            'name' => $request->name,
            'status' => $request->status
        ]);

        $brandUseCase = new UpdateBrandUseCase($this->brandRepository);
        $brandUpdated = $brandUseCase->execute($id, $userDTO);

        return response()->json(
            (new BrandResource($brandUpdated))->resolve(),
            200
        );
    }

    public function updateStatus(int $id, Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'status' => 'required|integer|in:0,1'
        ]);

        $status = $validatedData['status'];

        $brandUseCase = new UpdateStatusBrand($this->brandRepository);
        $brandUseCase->execute($id, $status);

        return response()->json(['message' => 'Estado actualizado correctamente'],200);
    }
}
