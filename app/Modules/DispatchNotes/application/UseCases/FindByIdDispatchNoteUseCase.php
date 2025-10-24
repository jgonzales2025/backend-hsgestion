<?php

namespace App\Modules\DispatchNotes\Application\UseCases;

use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;

class FindByIdDispatchNoteUseCase{
    public function __construct(private readonly DispatchNotesRepositoryInterface $dispatchNotesRepositoryInterface){
        
    }

    public function execute($id){
          return $this->dispatchNotesRepositoryInterface->findById($id);
    }
}