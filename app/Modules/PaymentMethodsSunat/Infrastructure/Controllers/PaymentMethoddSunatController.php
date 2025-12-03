<?php

namespace App\Modules\PaymentMethodsSunat\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\PaymentMethodsSunat\Application\DTO\PaymentMethodSunatDTO;
use App\Modules\PaymentMethodsSunat\Application\UseCases\CreatePaymentMethodSunatUseCase;
use App\Modules\PaymentMethodsSunat\Application\UseCases\FindAllPaymentMethodSunatUseCase;
use App\Modules\PaymentMethodsSunat\Application\UseCases\FindByIdPaymentMethodSunatUseCase;
use App\Modules\PaymentMethodsSunat\Domain\Interface\PaymentMethodSunatRepositoryInterface;
use App\Modules\PaymentMethodsSunat\Infrastructure\Request\StorePaymentMethodSunatRequest;
use App\Modules\PaymentMethodsSunat\Infrastructure\Resource\PaymentMethodSunatResource;
use Illuminate\Http\JsonResponse;

class PaymentMethoddSunatController extends Controller
{
    public function __construct(
        private readonly PaymentMethodSunatRepositoryInterface $repository
    ) {}

    public function index(): JsonResponse
    {
        $useCase = new FindAllPaymentMethodSunatUseCase($this->repository);
        $result = $useCase->execute();

        return response()->json(
            PaymentMethodSunatResource::collection($result),
            200
        );
    }

    public function show(int $cod): JsonResponse
    {
        $useCase = new FindByIdPaymentMethodSunatUseCase($this->repository);
        $result = $useCase->execute($cod);

        if (!$result) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json(
            new PaymentMethodSunatResource($result),
            200
        );
    }

    public function store(StorePaymentMethodSunatRequest $request): JsonResponse
    {
        $dto = PaymentMethodSunatDTO::fromRequest($request->validated());
        $useCase = new CreatePaymentMethodSunatUseCase($this->repository);
        $result = $useCase->execute($dto);

        return response()->json(
            new PaymentMethodSunatResource($result),
            201
        );
    }

    public function update(int $cod, \App\Modules\PaymentMethodsSunat\Infrastructure\Request\UpdatePaymentMethodSunatRequest $request): JsonResponse
    {
        $dto = PaymentMethodSunatDTO::fromRequest($request->validated());
        $useCase = new \App\Modules\PaymentMethodsSunat\Application\UseCases\UpdatePaymentMethodSunatUseCase($this->repository);
        $result = $useCase->execute($cod, $dto);

        if (!$result) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json(
            new PaymentMethodSunatResource($result),
            200
        );
    }
}
