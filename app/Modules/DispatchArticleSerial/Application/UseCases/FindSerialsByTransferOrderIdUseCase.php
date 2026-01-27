<?php

namespace App\Modules\DispatchArticleSerial\Application\UseCases;

use App\Modules\DispatchArticleSerial\Domain\Interfaces\DispatchArticleSerialRepositoryInterface;

class FindSerialsByTransferOrderIdUseCase
{
    public function __construct(private readonly DispatchArticleSerialRepositoryInterface $dispatchArticleSerialRepository){}

    public function execute(int $id): array
    {
        return $this->dispatchArticleSerialRepository->findSerialsByTransferOrderId($id);
    }
}