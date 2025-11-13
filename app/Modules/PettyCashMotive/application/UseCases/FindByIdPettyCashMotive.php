<?php

namespace App\Modules\PettyCashMotive\Application\UseCases;

use App\Modules\PettyCashMotive\Domain\Interface\PettyCashMotiveInterfaceRepository;

class FindByIdPettyCashMotive {
    public function __construct(private readonly PettyCashMotiveInterfaceRepository $pettyCashMotiveInterfaceRepository) {}
   public function execute(int $id) {
        return $this->pettyCashMotiveInterfaceRepository->findById($id);
    }

}