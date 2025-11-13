<?php

namespace App\Modules\DispatchNotes\Application\UseCases;

use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;

class UpdateStatusDispatchNoteUseCase{
    public function __construct(private readonly DispatchNotesRepositoryInterface $dispatchNotesRepositoryInterface){}
   
    public function execute(int $dispatchNoteId,int $status):void{
        $this->dispatchNotesRepositoryInterface->updateStatus($dispatchNoteId,$status);
    }

}