<?php

namespace App\Modules\PettyCashMotive\Application\UseCases;

use App\Modules\PettyCashMotive\Domain\Interface\PettyCashMotiveInterfaceRepository;

class UpdateStatusCashMotiveUseCase{
    public function __construct(private readonly PettyCashMotiveInterfaceRepository $pettyCashMotiveInterfaceRepository){}

    public function execute(int $id, int $status): void{
        $this->pettyCashMotiveInterfaceRepository->updateStatus($id, $status);
    }
}