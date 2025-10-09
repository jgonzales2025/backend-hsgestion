<?php

namespace App\Modules\TransportCompany\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\TransportCompany\Application\DTOs\TransportCompanyDTO;
use App\Modules\TransportCompany\Application\UseCases\CreateTransportCompanyUseCase;
use App\Modules\TransportCompany\Application\UseCases\FindAllTransportCompaniesUseCase;
use App\Modules\TransportCompany\Application\UseCases\FindByIdTransportCompanyUseCase;
use App\Modules\TransportCompany\Application\UseCases\UpdateTransportCompanyUseCase;
use App\Modules\TransportCompany\Domain\Interfaces\TransportCompanyRepositoryInterface;
use App\Modules\TransportCompany\Infrastructure\Requests\StoreTransportCompanyRequest;
use App\Modules\TransportCompany\Infrastructure\Requests\UpdateTransportCompanyRequest;
use App\Modules\TransportCompany\Infrastructure\Resources\TransportCompanyResource;
use Illuminate\Http\JsonResponse;

class TransportCompanyController extends Controller
{
    protected $transportCompanyRepository;

    public function __construct(TransportCompanyRepositoryInterface $transportCompanyRepository)
    {
        $this->transportCompanyRepository = $transportCompanyRepository;
    }

    public function index(): JsonResponse
    {
        $transportUseCase = new FindAllTransportCompaniesUseCase($this->transportCompanyRepository);
        $transportCompanies = $transportUseCase->execute();

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
        $transportCompanyDTO = new TransportCompanyDTO($request->validated());
        $transportUseCase = new UpdateTransportCompanyUseCase($this->transportCompanyRepository);
        $transportUseCase->execute($id, $transportCompanyDTO);

        $transportCompany = $this->transportCompanyRepository->findById($id);

        return response()->json(
            (new TransportCompanyResource($transportCompany))->resolve(),
              200
        );
    }
}
