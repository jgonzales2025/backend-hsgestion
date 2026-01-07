<?php

namespace App\Modules\ExchangeRate\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ExchangeRate\Application\DTOs\ExchangeRateDTO;
use App\Modules\ExchangeRate\Application\UseCases\FindAllExchangeRatesUseCase;
use App\Modules\ExchangeRate\Application\UseCases\FindByIdExchangeRateUseCase;
use App\Modules\ExchangeRate\Application\UseCases\FindExchangeRateUseCase;
use App\Modules\ExchangeRate\Application\UseCases\UpdateAlmacenUseCase;
use App\Modules\ExchangeRate\Application\UseCases\UpdateCobranzasUseCase;
use App\Modules\ExchangeRate\Application\UseCases\UpdateComprasUseCase;
use App\Modules\ExchangeRate\Application\UseCases\UpdateExchangeRateUseCase;
use App\Modules\ExchangeRate\Application\UseCases\UpdatePagosUseCase;
use App\Modules\ExchangeRate\Application\UseCases\UpdateVentasUseCase;
use App\Modules\ExchangeRate\Domain\Interfaces\ExchangeRateRepositoryInterface;
use App\Modules\ExchangeRate\Infrastructure\Requests\UpdateExchangeRateRequest;
use App\Modules\ExchangeRate\Infrastructure\Resources\ExchangeRateResource;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    public function __construct(private readonly ExchangeRateRepositoryInterface $exchangeRateRepository){}

    public function index(Request $request): JsonResponse
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $exchangeRateUseCase = new FindAllExchangeRatesUseCase($this->exchangeRateRepository);
        $exchangeRates = $exchangeRateUseCase->execute($startDate, $endDate);

        return new JsonResponse([
            'data' => ExchangeRateResource::collection($exchangeRates)->resolve(),
            'current_page' => $exchangeRates->currentPage(),
            'per_page' => $exchangeRates->perPage(),
            'total' => $exchangeRates->total(),
            'last_page' => $exchangeRates->lastPage(),
            'next_page_url' => $exchangeRates->nextPageUrl(),
            'prev_page_url' => $exchangeRates->previousPageUrl(),
            'first_page_url' => $exchangeRates->url(1),
            'last_page_url' => $exchangeRates->url($exchangeRates->lastPage()),
        ]);
    }

    public function current(): JsonResponse
    {
        $exchangeRateUseCase = new FindExchangeRateUseCase($this->exchangeRateRepository);
        $exchangeRate = $exchangeRateUseCase->execute();

        return response()->json(new ExchangeRateResource($exchangeRate), 200);
    }

    public function show($id): JsonResponse
    {
        $exchangeRateUseCase = new FindByIdExchangeRateUseCase($this->exchangeRateRepository);
        $exchangeRate = $exchangeRateUseCase->execute($id);

        return response()->json(new ExchangeRateResource($exchangeRate), 200);
    }

    public function update(UpdateExchangeRateRequest $request, $id): JsonResponse
    {
        $exchangeRateDTO = new ExchangeRateDTO($request->validated());
        $exchangeRateUseCase = new UpdateExchangeRateUseCase($this->exchangeRateRepository);
        $exchangeRate = $exchangeRateUseCase->execute($id, $exchangeRateDTO);

        return response()->json(new ExchangeRateResource($exchangeRate), 200);
    }

    public function updateAlmacen($id, Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'status' => 'required|integer|in:0,1',
        ]);
        $exchangeRateUseCase = new UpdateAlmacenUseCase($this->exchangeRateRepository);
        $exchangeRateUseCase->execute($id, $validatedData['status']);

        return response()->json(['message' => 'Estado actualizado correctamente.'], 200);
    }

    public function updateCompras($id, Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'status' => 'required|integer|in:0,1',
        ]);
        $exchangeRateUseCase = new UpdateComprasUseCase($this->exchangeRateRepository);
        $exchangeRateUseCase->execute($id, $validatedData['status']);

        return response()->json(['message' => 'Estado actualizado correctamente.'], 200);
    }

    public function updateVentas($id, Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'status' => 'required|integer|in:0,1',
        ]);
        $exchangeRateUseCase = new UpdateVentasUseCase($this->exchangeRateRepository);
        $exchangeRateUseCase->execute($id, $validatedData['status']);

        return response()->json(['message' => 'Estado actualizado correctamente.'], 200);
    }

    public function updateCobranzas($id, Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'status' => 'required|integer|in:0,1',
        ]);
        $exchangeRateUseCase = new UpdateCobranzasUseCase($this->exchangeRateRepository);
        $exchangeRateUseCase->execute($id, $validatedData['status']);

        return response()->json(['message' => 'Estado actualizado correctamente.'], 200);
    }

    public function updatePagos($id, Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'status' => 'required|integer|in:0,1',
        ]);
        $exchangeRateUseCase = new UpdatePagosUseCase($this->exchangeRateRepository);
        $exchangeRateUseCase->execute($id, $validatedData['status']);

        return response()->json(['message' => 'Estado actualizado correctamente.'], 200);
    }
}
