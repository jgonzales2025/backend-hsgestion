<?php

namespace App\Modules\Advance\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Advance\Application\UseCases\FindByCustomerIdUseCase;
use App\Modules\Advance\Domain\Interfaces\AdvanceRepositoryInterface;
use App\Modules\Advance\Infrastructure\Resources\AdvanceResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdvanceController extends Controller
{
    public function __construct(private AdvanceRepositoryInterface $advanceRepository)
    {
    }

    public function show($customerId): JsonResponse
    {
        $advanceUseCase = new FindByCustomerIdUseCase($this->advanceRepository);
        $advance = $advanceUseCase->execute($customerId);

        return response()->json((new AdvanceResource($advance))->resolve());
    }
}