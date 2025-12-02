<?php

namespace App\Modules\Withholding\Infrastructure\Controller;

use App\Http\Controllers\Controller;
use App\Modules\Withholding\Application\UseCases\FindByDateWithholdingUseCase;
use App\Modules\Withholding\Domain\Interface\WithholdingRepositoryInterface;
use App\Modules\Withholding\Infrastructure\Resources\WithholdingResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class WithholdingController extends Controller
{
    public function __construct(
        private WithholdingRepositoryInterface $withholdingRepository
    ) {}

    public function findByDate(string $date): JsonResponse
    {
        $withholdingUseCase = new FindByDateWithholdingUseCase($this->withholdingRepository);
        $withholding = $withholdingUseCase->execute($date);

        return response()->json((new WithholdingResource($withholding))->resolve());
    }
}