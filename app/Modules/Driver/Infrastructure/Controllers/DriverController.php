<?php

namespace App\Modules\Driver\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Driver\Application\DTOs\DriverDTO;
use App\Modules\Driver\Application\UseCases\CreateDriverUseCase;
use App\Modules\Driver\Application\UseCases\FindAllDriversUseCases;
use App\Modules\Driver\Application\UseCases\FindByIdDriverUseCase;
use App\Modules\Driver\Application\UseCases\FindDriverByDocumentUseCase;
use App\Modules\Driver\Application\UseCases\UpdateDriverUseCase;
use App\Modules\Driver\Application\UseCases\UpdateStatusDriverUseCase;
use App\Modules\Driver\Infrastructure\Persistence\EloquentDriverRepository;
use App\Modules\Driver\Infrastructure\Requests\StoreDriverRequest;
use App\Modules\Driver\Infrastructure\Requests\UpdateDriverRequest;
use App\Modules\Driver\Infrastructure\Resources\DriverResource;
use App\Services\ApiSunatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    protected $driverRepository;

    public function __construct(  private readonly ApiSunatService $apiSunatService)
    {
        $this->driverRepository = new EloquentDriverRepository();
    }

    public function index(Request $request): JsonResponse
    {
        $description = $request->query('description');
        $status = $request->query('status') !== null ? (int) $request->query('status') : null;
        $branchUseCase = new FindAllDriversUseCases($this->driverRepository);
        $drivers = $branchUseCase->execute($description, $status);

        return new JsonResponse([
            'data' => DriverResource::collection($drivers)->resolve(),
            'current_page' => $drivers->currentPage(),
            'per_page' => $drivers->perPage(),
            'total' => $drivers->total(),
            'last_page' => $drivers->lastPage(),
            'next_page_url' => $drivers->nextPageUrl(),
            'prev_page_url' => $drivers->previousPageUrl(),
            'first_page_url' => $drivers->url(1),
            'last_page_url' => $drivers->url($drivers->lastPage()),
        ]);
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
    public function storeCustomerBySunatApi(Request $request): JsonResponse
    {
        $documentNumber = $request->query('document_number');

        if (!$documentNumber) {
            return response()->json(['error' => 'No ha enviado el número de documento'], 422);
        }
        if (!in_array(strlen($documentNumber), [8, 11])) {
            return response()->json(['error' => 'Número de documento no válido'], 422);
        }
        $data = $this->apiSunatService->getDataByDocument($documentNumber);

        if (!$data['success']) {
            return response()->json(['error' => 'Documento no válido'], 422);
        }

        $transportUseCase = new FindDriverByDocumentUseCase($this->driverRepository);
        $customer = $transportUseCase->execute($documentNumber);

        if ($customer) {
            return response()->json(['error' => 'Cliente ya existe'], 409);
        }


        $documentNumberValue = $data['data']['ruc'] ?? $data['data']['document_number'];

        $customerDTO = new DriverDTO([
        'customer_document_type_id' => strlen($documentNumberValue) === 11 ? 2 : 1,
        'doc_number' => $documentNumberValue,
        'name' => $data['data']['first_name'] ?? null,
        'pat_surname' => $data['data']['first_last_name'] ?? null,
        'mat_surname' => $data['data']['second_last_name'] ?? null,
        'status'=>1 ,
        'license' => null,
        ]);

        $customerUseCase = new CreateDriverUseCase($this->driverRepository);
        $customer = $customerUseCase->execute($customerDTO);

        return response()->json(
         new DriverResource($customer), 201);
    }

    public function updateStatus(int $id, Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'status' => 'required|integer|in:0,1',
        ]);

        $status = $validatedData['status'];

        $driverUseCase = new UpdateStatusDriverUseCase($this->driverRepository);
        $driverUseCase->execute($id, $status);

        return response()->json(['message' => 'Estado actualizado correctamente'], 200);
    }
}
