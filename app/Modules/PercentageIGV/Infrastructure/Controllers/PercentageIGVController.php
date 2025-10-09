<?php

namespace App\Modules\PercentageIGV\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\PercentageIGV\Application\DTOs\PercentageIGVDTO;
use App\Modules\PercentageIGV\Application\UseCases\CreatePercentageIGVUseCase;
use App\Modules\PercentageIGV\Application\UseCases\FindAllPercentageIGVUseCase;
use App\Modules\PercentageIGV\Application\UseCases\FindByIdPercentageIGVUseCase;
use App\Modules\PercentageIGV\Application\UseCases\UpdatePercentageIGVUseCase;
use App\Modules\PercentageIGV\Domain\Interfaces\PercentageIGVRepositoryInterface;
use App\Modules\PercentageIGV\Infrastructure\Requests\StorePercentageIGVRequest;
use App\Modules\PercentageIGV\Infrastructure\Requests\UpdatePercentageIGVRequest;
use App\Modules\PercentageIGV\Infrastructure\Resources\PercentageIGVResource;
use Illuminate\Http\JsonResponse;

class PercentageIGVController extends Controller
{
    protected $percentageIGVRepository;

    public function __construct(PercentageIGVRepositoryInterface $percentageIGVRepository)
    {
        $this->percentageIGVRepository = $percentageIGVRepository;
    }

    public function index(): array
    {
        $percentageIGVs = new FindAllPercentageIGVUseCase($this->percentageIGVRepository);
        $percentageIGVs = $percentageIGVs->execute();
        return PercentageIGVResource::collection($percentageIGVs)->resolve();
    }

    public function store(StorePercentageIGVRequest $request): JsonResponse
    {
        $percentageIGVDTO = new PercentageIGVDTO($request->validated());
        $percentageIGVUseCase = new CreatePercentageIGVUseCase($this->percentageIGVRepository);
        $percentageIGV = $percentageIGVUseCase->execute($percentageIGVDTO);

        return response()->json(new PercentageIGVResource($percentageIGV), 201);
    }

    public function show(int $id): JsonResponse
    {
        $percentageIGVUseCase = new FindByIdPercentageIGVUseCase($this->percentageIGVRepository);
        $percentageIGV = $percentageIGVUseCase->execute($id);

        if (!$percentageIGV) {
            return response()->json(['message' => 'Percentage IGV not found'], 404);
        }

        return response()->json(new PercentageIGVResource($percentageIGV), 200);
    }

    public function update(int $id, UpdatePercentageIGVRequest $request): JsonResponse
    {
        $percentageIGVDTO = new PercentageIGVDTO($request->validated());
        $percentageIGVUseCase = new UpdatePercentageIGVUseCase($this->percentageIGVRepository);
        $percentageIGV = $percentageIGVUseCase->execute($id, $percentageIGVDTO);

        if (!$percentageIGV) {
            return response()->json(['message' => 'Percentage IGV not found or could not be updated'], 404);
        }

        return response()->json(new PercentageIGVResource($percentageIGV), 200);
    }
}
