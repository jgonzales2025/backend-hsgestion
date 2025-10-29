<?php

namespace App\Modules\MeasurementUnit\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MeasurementUnit\Application\DTOs\MeasurementUnitDTO;
use App\Modules\MeasurementUnit\Application\UseCases\CreateMeasurementUnitUseCase;
use App\Modules\MeasurementUnit\Application\UseCases\FindAllMeasurementUnitUseCase;
use App\Modules\MeasurementUnit\Application\UseCases\FindByIdMeasurementUnit;
use App\Modules\MeasurementUnit\Application\UseCases\UpdateMeasurementUnitUseCase;
use App\Modules\MeasurementUnit\Domain\Interfaces\MeasurementUnitRepositoryInterface;
use App\Modules\MeasurementUnit\Infrastructure\Requests\StoreMeasurementUnitRequest;
use App\Modules\MeasurementUnit\Infrastructure\Requests\UpdateMeasurementUnitRequest;
use App\Modules\MeasurementUnit\Infrastructure\Resources\MeasurementUnitResource;
use Illuminate\Http\JsonResponse;

class MeasurementUnitController extends Controller
{

    protected $measurementUnitRepository;

    public function __construct(MeasurementUnitRepositoryInterface $measurementUnitRepository)
    {
        $this->measurementUnitRepository = $measurementUnitRepository;
    }

    public function index(): array
    {
        $measurementUnitUseCase = new FindAllMeasurementUnitUseCase($this->measurementUnitRepository);
        $measurementUnits = $measurementUnitUseCase->execute();

        return MeasurementUnitResource::collection($measurementUnits)->resolve();
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
            return response()->json(['message' => 'Measurement Unit not found'], 404);
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
}
