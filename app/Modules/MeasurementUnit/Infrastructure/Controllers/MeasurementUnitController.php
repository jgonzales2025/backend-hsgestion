<?php

namespace App\Modules\MeasurementUnit\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MeasurementUnit\Application\DTOs\MeasurementUnitDTO;
use App\Modules\MeasurementUnit\Application\UseCases\CreateMeasurementUnitUseCase;
use App\Modules\MeasurementUnit\Application\UseCases\FindAllMeasurementUnitUseCase;
use App\Modules\MeasurementUnit\Application\UseCases\FindAllPaginateInfiniteMeasurementUnitUseCase;
use App\Modules\MeasurementUnit\Application\UseCases\FindByIdMeasurementUnit;
use App\Modules\MeasurementUnit\Application\UseCases\UpdateMeasurementUnitUseCase;
use App\Modules\MeasurementUnit\Application\UseCases\UpdateStatusMeasurementUnitUseCase;
use App\Modules\MeasurementUnit\Domain\Interfaces\MeasurementUnitRepositoryInterface;
use App\Modules\MeasurementUnit\Infrastructure\Requests\StoreMeasurementUnitRequest;
use App\Modules\MeasurementUnit\Infrastructure\Requests\UpdateMeasurementUnitRequest;
use App\Modules\MeasurementUnit\Infrastructure\Resources\MeasurementUnitResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MeasurementUnitController extends Controller
{

    protected $measurementUnitRepository;

    public function __construct(MeasurementUnitRepositoryInterface $measurementUnitRepository)
    {
        $this->measurementUnitRepository = $measurementUnitRepository;
    }

    public function indexPaginateInfinite()
    {
        $measurementUnitUseCase = new FindAllPaginateInfiniteMeasurementUnitUseCase($this->measurementUnitRepository);
        $measurementUnits = $measurementUnitUseCase->execute();

        return new JsonResponse([
            'data' => MeasurementUnitResource::collection($measurementUnits)->resolve(),
            'next_cursor' => $measurementUnits->nextCursor()?->encode(),
            'prev_cursor' => $measurementUnits->previousCursor()?->encode(),
            'next_page_url' => $measurementUnits->nextPageUrl(),
            'prev_page_url' => $measurementUnits->previousPageUrl(),
            'per_page' => $measurementUnits->perPage()
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $description = $request->query('description');
        $status = $request->query('status') !== null ? (int) $request->query('status') : null;

        $measurementUnitUseCase = new FindAllMeasurementUnitUseCase($this->measurementUnitRepository);
        $measurementUnits = $measurementUnitUseCase->execute($description, $status);

        return new JsonResponse([
            'data' => MeasurementUnitResource::collection($measurementUnits)->resolve(),
            'current_page' => $measurementUnits->currentPage(),
            'per_page' => $measurementUnits->perPage(),
            'total' => $measurementUnits->total(),
            'last_page' => $measurementUnits->lastPage(),
            'next_page_url' => $measurementUnits->nextPageUrl(),
            'prev_page_url' => $measurementUnits->previousPageUrl(),
            'first_page_url' => $measurementUnits->url(1),
            'last_page_url' => $measurementUnits->url($measurementUnits->lastPage()),
        ]);
    }

    public function store(StoreMeasurementUnitRequest $request): JsonResponse
    {
        $measurementUnitDTO = new MeasurementUnitDTO($request->validated());
        $measurementUnitUseCase = new CreateMeasurementUnitUseCase($this->measurementUnitRepository);
        $measurementUnit = $measurementUnitUseCase->execute($measurementUnitDTO);

        return response()->json(new MeasurementUnitResource($measurementUnit),201);
    }

    public function show(int $id): JsonResponse
    {
        $measurementUnitUseCase = new FindByIdMeasurementUnit($this->measurementUnitRepository);
        $measurementUnit = $measurementUnitUseCase->execute($id);

        if (!$measurementUnit) {
            return response()->json(['message' => 'Unidad de medida no encontrada'], 404);
        }

        return response()->json(new MeasurementUnitResource($measurementUnit));
    }

    public function update(int $id, UpdateMeasurementUnitRequest $request): JsonResponse
    {
        $measurementUnitUseCase = new FindByIdMeasurementUnit($this->measurementUnitRepository);
        $measurementUnit = $measurementUnitUseCase->execute($id);

        if (!$measurementUnit) {
            return response()->json(['message' => 'Unidad de medida no encontrada'], 404);
        }

        $measurementUnitDTO = new MeasurementUnitDTO($request->validated());
        $measurementUnitUpdateUseCase = new UpdateMeasurementUnitUseCase($this->measurementUnitRepository);
        $measurementUnitUpdate = $measurementUnitUpdateUseCase->execute($measurementUnit, $measurementUnitDTO);

        return response()->json(new MeasurementUnitResource($measurementUnitUpdate));
    }

    public function updateStatus(int $id, Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'status' => 'required|integer|in:0,1',
        ]);
        $status = $validatedData['status'];

        $updateStatusMeasurementUnitUseCase = new UpdateStatusMeasurementUnitUseCase($this->measurementUnitRepository);
        $updateStatusMeasurementUnitUseCase->execute($id, $status);

        return response()->json(['message' => 'Estado actualizado correctamente']);
    }
}
