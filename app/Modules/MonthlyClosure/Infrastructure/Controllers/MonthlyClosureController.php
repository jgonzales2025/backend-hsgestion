<?php

namespace App\Modules\MonthlyClosure\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MonthlyClosure\Application\DTOs\MonthlyClosureDTO;
use App\Modules\MonthlyClosure\Application\UseCases\CreateMonthlyClosureUseCase;
use App\Modules\MonthlyClosure\Application\UseCases\FindAllMonthlyClosuresUseCase;
use App\Modules\MonthlyClosure\Application\UseCases\FindByIdMonthlyClosureUseCase;
use App\Modules\MonthlyClosure\Application\UseCases\UpdateStSalesUseCase;
use App\Modules\MonthlyClosure\Domain\Interfaces\MonthlyClosureRepositoryInterface;
use App\Modules\MonthlyClosure\Infrastructure\Requests\StoreMonthlyClosureRequest;
use App\Modules\MonthlyClosure\Infrastructure\Resources\MonthlyClosureResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MonthlyClosureController extends Controller
{
    public function __construct(private readonly MonthlyClosureRepositoryInterface $monthlyClosureRepository){}

    public function index(): array
    {
        $monthlyClosureUseCase = new FindAllMonthlyClosuresUseCase($this->monthlyClosureRepository);
        $monthlyClosure = $monthlyClosureUseCase->execute();

        return MonthlyClosureResource::collection($monthlyClosure)->resolve();
    }

    public function store(StoreMonthlyClosureRequest $request): JsonResponse
    {
        $monthlyClosureDTO = new MonthlyClosureDTO($request->validated());
        $monthlyClosureUseCase = new CreateMonthlyClosureUseCase($this->monthlyClosureRepository);
        $monthlyClosure = $monthlyClosureUseCase->execute($monthlyClosureDTO);

        return response()->json(new MonthlyClosureResource($monthlyClosure), 201);
    }

    public function show(int $id): JsonResponse
    {
        $monthlyClosureUseCase = new FindByIdMonthlyClosureUseCase($this->monthlyClosureRepository);
        $monthlyClosure = $monthlyClosureUseCase->execute($id);

        return response()->json((new MonthlyClosureResource($monthlyClosure))->resolve(),200);
    }

    public function updateStSales(int $id, Request $request): JsonResponse
    {
        $status = $request->query('status');

        if (!in_array($status, [0, 1])) {
            return response()->json(['message' => 'El estado de ventas debe ser 0 o 1'], 400);
        }

        $monthlyClosureUseCase = new FindByIdMonthlyClosureUseCase($this->monthlyClosureRepository);
        $monthlyClosure = $monthlyClosureUseCase->execute($id);

        if (!$monthlyClosure) {
            return response()->json(['message' => 'Cierre de mes no encontrado'], 404);
        }

        $monthlyClosureUpdateStSalesUseCase = new UpdateStSalesUseCase($this->monthlyClosureRepository);
        $monthlyClosureUpdateStSalesUseCase->execute($monthlyClosure->getId(), $status);

        return response()->json(['message' => 'Estado de ventas actualizado correctamente'], 200);
    }
}
