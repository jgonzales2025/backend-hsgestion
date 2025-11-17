<?php

namespace App\Modules\DispatchArticleSerial\Application\UseCases;

use App\Modules\DispatchArticleSerial\Application\DTOs\DispatchArticleSerialDTO;
use App\Modules\DispatchArticleSerial\Infrastructure\Persistence\EloquentDispatchArticleSerialRepository;

class FindAllTransferMovementsUseCase
{
    private $dispatchArticleSerialRepository;

    public function __construct(EloquentDispatchArticleSerialRepository $dispatchArticleSerialRepository)
    {
        $this->dispatchArticleSerialRepository = $dispatchArticleSerialRepository;
    }

    public function execute(int $branchId): array
    {
        $dispatchArticleSerials = $this->dispatchArticleSerialRepository->findAllTransferMovements($branchId);

        return $dispatchArticleSerials;
    }
}
