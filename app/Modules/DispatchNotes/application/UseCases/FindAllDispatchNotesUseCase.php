<?php

namespace App\Modules\DispatchNotes\Application\UseCases;

use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;

class FindAllDispatchNotesUseCase{

    public function __construct(private readonly DispatchNotesRepositoryInterface $dispatchNotesRepositoryInterface){
        
    }

    public function execute():array{
        return $this->dispatchNotesRepositoryInterface->findAll();
    }
}