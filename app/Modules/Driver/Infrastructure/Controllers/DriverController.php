<?php

namespace App\Modules\Driver\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Driver\Application\DTOs\DriverDTO;
use App\Modules\Driver\Application\UseCases\CreateDriverUseCase;
use App\Modules\Driver\Application\UseCases\FindAllDriversUseCases;
use App\Modules\Driver\Application\UseCases\FindByIdDriverUseCase;
use App\Modules\Driver\Application\UseCases\UpdateDriverUseCase;
use App\Modules\Driver\Infrastructure\Persistence\EloquentDriverRepository;
use App\Modules\Driver\Infrastructure\Requests\StoreDriverRequest;
use App\Modules\Driver\Infrastructure\Requests\UpdateDriverRequest;
use App\Modules\Driver\Infrastructure\Resources\DriverResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    protected $driverRepository;

    public function __construct()
    {
        $this->driverRepository = new EloquentDriverRepository();
    }

    public function index(Request $request): array
    {
        $description = $request->query('description');
        $branchUseCase = new FindAllDriversUseCases($this->driverRepository);
        $drivers = $branchUseCase->execute($description);

        return DriverResource::collection($drivers)->resolve();
    }

    public function store(StoreDriverRequest $request): JsonResponse
    {
        $driverDTO = new DriverDTO($request->validated());
        $driverUseCase = new CreateDriverUseCase($this->driverRepository);
        $driver = $driverUseCase->execute($driverDTO);

        return response()->json(
            (new DriverResource($driver))->resolve(),
            201
        );
    }

    public function show(int $id): JsonResponse
    {
        $driverUseCase = new FindByIdDriverUseCase($this->driverRepository);
        $driver = $driverUseCase->execute($id);

        return response()->json(
            (new DriverResource($driver))->resolve(),
            200
        );
    }

    public function update(UpdateDriverRequest $request, int $id): JsonResponse
    {
        $driverDTO = new DriverDTO(array_merge(
            $request->validated(),
            ['id' => $id]
        ));

        $driverUseCase = new UpdateDriverUseCase($this->driverRepository);
        $driverUseCase->execute($id, $driverDTO);

        $driver = $this->driverRepository->findById($id);

        return response()->json(
            (new DriverResource($driver))->resolve(),
            200
        );
    }
}
