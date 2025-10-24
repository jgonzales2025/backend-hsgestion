<?php

namespace App\Modules\EmissionReason\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\EmissionReason\Application\UseCases\FindAllEmissionReasonUseCase;
use App\Modules\EmissionReason\Application\UseCases\FindByIdEmissionReasonUseCase;
use App\Modules\EmissionReason\Domain\Interfaces\EmissionReasonRepositoryInterface;
use App\Modules\EmissionReason\Infrastructure\Resources\EmissionReasonResource;
use Illuminate\Http\JsonResponse;

class EmissionReasonController extends Controller
{

    public function __construct(private readonly EmissionReasonRepositoryInterface $emissionReasonRepository){}

    public function index(): array
    {
        $emissionReasonUseCase = new FindAllEmissionReasonUseCase($this->emissionReasonRepository);
        $emissionReasons = $emissionReasonUseCase->execute();

        return EmissionReasonResource::collection($emissionReasons)->resolve();
    }
       public function show($id): JsonResponse
    {
        $emissionReasonUseCase = new FindByIdEmissionReasonUseCase($this->emissionReasonRepository);
        $emissionReasons = $emissionReasonUseCase->execute($id);

        return response()->json(
            new EmissionReasonResource($emissionReasons),200
        );
    }
}
