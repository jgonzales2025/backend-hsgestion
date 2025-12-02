<?php

namespace App\Modules\Detraction\Infrastructure\Controller;

use App\Http\Controllers\Controller;
use App\Modules\Detraction\Application\UseCases\FindAllDetractionsUseCase;
use App\Modules\Detraction\Domain\Interface\DetractionRepositoryInterface;
use App\Modules\Detraction\Infrastructure\Resources\DetractionResource;

class DetractionController extends Controller
{
    public function __construct(
        private DetractionRepositoryInterface $detractionRepository,
    ) {
    }

    public function index(): array
    {
        $detractionsUseCase = new FindAllDetractionsUseCase($this->detractionRepository);
        $detractions = $detractionsUseCase->execute();
        return DetractionResource::collection($detractions)->resolve();
    }
}