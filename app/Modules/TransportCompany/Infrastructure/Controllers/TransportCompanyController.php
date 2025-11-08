<?php

namespace App\Modules\TransportCompany\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Customer\Application\UseCases\CreateCustomerSunatApiUseCase;
use App\Modules\TransportCompany\Application\DTOs\TransportCompanyDTO;
use App\Modules\TransportCompany\Application\UseCases\CreateTransportCompanyUseCase;
use App\Modules\TransportCompany\Application\UseCases\FindAllPublicTransportUseCase;
use App\Modules\TransportCompany\Application\UseCases\FindAllTransportCompaniesUseCase;
use App\Modules\TransportCompany\Application\UseCases\FindByIdTransportCompanyUseCase;
use App\Modules\TransportCompany\Application\UseCases\FindCompanyTransport;
use App\Modules\TransportCompany\Application\UseCases\FindPrivateTransportUseCase;
use App\Modules\TransportCompany\Application\UseCases\UpdateTransportCompanyUseCase;
use App\Modules\TransportCompany\Domain\Entities\TransportCompany;
use App\Modules\TransportCompany\Domain\Interfaces\TransportCompanyRepositoryInterface;
use App\Modules\TransportCompany\Infrastructure\Requests\StoreTransportCompanyRequest;
use App\Modules\TransportCompany\Infrastructure\Requests\UpdateTransportCompanyRequest;
use App\Modules\TransportCompany\Infrastructure\Resources\TransportCompanyResource;
use App\Services\ApiSunatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransportCompanyController extends Controller
{
    protected $transportCompanyRepository;

    public function __construct(
        TransportCompanyRepositoryInterface $transportCompanyRepository,
        private readonly ApiSunatService $apiSunatService
    ) {
        $this->transportCompanyRepository = $transportCompanyRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $description = $request->query('description');
        $transportUseCase = new FindAllTransportCompaniesUseCase($this->transportCompanyRepository);
        $transportCompanies = $transportUseCase->execute($description);

        return response()->json((TransportCompanyResource::collection($transportCompanies))->resolve());
    }

    public function store(StoreTransportCompanyRequest $request): JsonResponse
    {

        $transportCompanyDTO = new TransportCompanyDTO($request->validated());
        $transportUseCase = new CreateTransportCompanyUseCase($this->transportCompanyRepository);
        $transportCompany = $transportUseCase->execute($transportCompanyDTO);

        return response()->json(
            (new TransportCompanyResource($transportCompany))->resolve(),
            201
        );
    }

    public function show($id): JsonResponse
    {
        $transportUseCase = new FindByIdTransportCompanyUseCase($this->transportCompanyRepository);
        $transportCompany = $transportUseCase->execute($id);

        return response()->json(
            (new TransportCompanyResource($transportCompany))->resolve(),
            200
        );
    }

    public function update(UpdateTransportCompanyRequest $request, $id): JsonResponse
    {
        $transportUseCase = new FindByIdTransportCompanyUseCase($this->transportCompanyRepository);
        $transportCompany = $transportUseCase->execute($id);

        if (!$transportCompany) {
            return response()->json(['message' => 'Transporte no encontrado'], 404);
        }

        $transportCompanyDTO = new TransportCompanyDTO($request->validated());
        $transportUpdateUseCase = new UpdateTransportCompanyUseCase($this->transportCompanyRepository);
        $transportUpdate = $transportUpdateUseCase->execute($transportCompany, $transportCompanyDTO);

        return response()->json(
            (new TransportCompanyResource($transportUpdate))->resolve(),
            200
        );
    }

    public function findPrivateTransport(): JsonResponse
    {
        $transportUseCase = new FindPrivateTransportUseCase($this->transportCompanyRepository);
        $transportCompany = $transportUseCase->execute();

        return response()->json(
            (new TransportCompanyResource($transportCompany))->resolve(),
            200
        );
    }

    public function indexPublicTransport(Request $request): array
    {
          $description = $request->query('description');
   
        $transportUseCase = new FindAllPublicTransportUseCase($this->transportCompanyRepository);
        $transportCompanies = $transportUseCase->execute($description);

        return TransportCompanyResource::collection($transportCompanies)->resolve();
    }

    public function storeCustomerBySunatApi(Request $request): JsonResponse
    {
        $documentNumber = $request->query('document_number');

        if (!$documentNumber) {
            return response()->json(['error' => 'No ha enviado el número de documento'], 422);
        }

        $data = $this->apiSunatService->getDataByDocument($documentNumber);

        if (!$data['success']) {
            return response()->json(['error' => 'Documento no válido'], 422);
        }

        $transportUseCase = new FindCompanyTransport($this->transportCompanyRepository);
        $customer = $transportUseCase->execute($documentNumber);

        if ($customer) {
            return response()->json(['error' => 'Cliente ya existe'], 409);
        }

        $documentNumberValue = $data['data']['ruc'] ?? $data['data']['document_number'];

        $customerDTO = new TransportCompanyDTO([

            'ruc' => $documentNumberValue,
            'company_name' => $data['data']['razsoc'] ?? null,
            'address' => $data['data']['direccion'] ?? null,
            'nro_reg_mtc' => '',
            'status' => null,

        ]);

        $customerUseCase = new CreateTransportCompanyUseCase($this->transportCompanyRepository);
        $customer = $customerUseCase->execute($customerDTO);

        return response()->json([
            'customer' => (new TransportCompanyResource($customer))->resolve(),
        ], 201);
    }
    
}
