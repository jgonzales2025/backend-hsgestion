<?php

namespace App\Modules\PettyCashMotive\Application\UseCases;
use App\Modules\PettyCashMotive\Domain\Interface\PettyCashMotiveInterfaceRepository;

class FindAllPettyCashMotive{
    public function __construct(private readonly PettyCashMotiveInterfaceRepository $pettyCashMotiveInterfaceRepository){}

    public function execute(?string $receipt_type){
        return $this->pettyCashMotiveInterfaceRepository->findAll($receipt_type);
    
    }
}