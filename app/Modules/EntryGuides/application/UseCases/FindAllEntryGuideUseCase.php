<?php

namespace App\Modules\EntryGuides\Application\UseCases;

use App\Modules\EntryGuides\Domain\Interfaces\EntryGuideRepositoryInterface;



class FindAllEntryGuideUseCase{
     public function __construct(private readonly EntryGuideRepositoryInterface $entryGuideRepositoryInterface){}
    

     public function execute(?string $serie,?string $correlativo){
        return $this->entryGuideRepositoryInterface->findAll($serie, $correlativo);
     }
    }