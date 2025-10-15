<?php

namespace App\Modules\EmissionReason\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\EmissionReason\Application\UseCases\FindAllEmissionReasonUseCase;
use App\Modules\EmissionReason\Domain\Interfaces\EmissionReasonRepositoryInterface;
use App\Modules\EmissionReason\Infrastructure\Resources\EmissionReasonResource;

class EmissionReasonController extends Controller
{

    public function __construct(private readonly EmissionReasonRepositoryInterface $emissionReasonRepository){}

    public function index(): array
    {
        $emissionReasonUseCase = new FindAllEmissionReasonUseCase($this->emissionReasonRepository);
        $emissionReasons = $emissionReasonUseCase->execute();

        return EmissionReasonResource::collection($emissionReasons)->resolve();
    }
}
