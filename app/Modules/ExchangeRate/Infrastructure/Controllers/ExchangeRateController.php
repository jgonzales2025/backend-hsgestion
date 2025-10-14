<?php

namespace App\Modules\ExchangeRate\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ExchangeRate\Application\UseCases\FindExchangeRateUseCase;
use App\Modules\ExchangeRate\Domain\Interfaces\ExchangeRateRepositoryInterface;
use App\Modules\ExchangeRate\Infrastructure\Resources\ExchangeRateResource;
use Illuminate\Http\JsonResponse;

class ExchangeRateController extends Controller
{
    public function __construct(private readonly ExchangeRateRepositoryInterface $exchangeRateRepository){}

    public function index(): JsonResponse
    {
        $exchangeRateUseCase = new FindExchangeRateUseCase($this->exchangeRateRepository);
        $exchangeRate = $exchangeRateUseCase->execute();

        return response()->json(new ExchangeRateResource($exchangeRate), 200);
    }
}
