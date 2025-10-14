<?php

namespace App\Modules\ExchangeRate\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ExchangeRate\Application\DTOs\ExchangeRateDTO;
use App\Modules\ExchangeRate\Application\UseCases\FindAllExchangeRatesUseCase;
use App\Modules\ExchangeRate\Application\UseCases\FindByIdExchangeRateUseCase;
use App\Modules\ExchangeRate\Application\UseCases\FindExchangeRateUseCase;
use App\Modules\ExchangeRate\Application\UseCases\UpdateExchangeRateUseCase;
use App\Modules\ExchangeRate\Domain\Interfaces\ExchangeRateRepositoryInterface;
use App\Modules\ExchangeRate\Infrastructure\Requests\UpdateExchangeRateRequest;
use App\Modules\ExchangeRate\Infrastructure\Resources\ExchangeRateResource;
use Illuminate\Http\JsonResponse;

class ExchangeRateController extends Controller
{
    public function __construct(private readonly ExchangeRateRepositoryInterface $exchangeRateRepository){}

    public function index(): array
    {
        $exchangeRateUseCase = new FindAllExchangeRatesUseCase($this->exchangeRateRepository);
        $exchangeRates = $exchangeRateUseCase->execute();

        return ExchangeRateResource::collection($exchangeRates)->resolve();
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
}
