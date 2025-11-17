<?php

namespace App\Modules\DispatchArticleSerial\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\DispatchArticleSerial\Application\UseCases\FindAllTransferMovementsUseCase;
use App\Modules\DispatchArticleSerial\Domain\Interfaces\DispatchArticleSerialRepositoryInterface;
use App\Modules\DispatchArticleSerial\Infrastructure\Resources\DispatchArticleSerialResource;

class DispatchArticleSerialController extends Controller
{
    public function __construct(private DispatchArticleSerialRepositoryInterface $dispatchArticleSerialRepository){}

    public function findAllMovements(int $branchId): array
    {
        $dispatchArticleSerialsUseCase = new FindAllTransferMovementsUseCase($this->dispatchArticleSerialRepository);
        $dispatchArticleSerials = $dispatchArticleSerialsUseCase->execute($branchId);

        return DispatchArticleSerialResource::collection($dispatchArticleSerials)->resolve();
    }
}