<?php

namespace App\Modules\EntryGuides\Application\UseCases;

use App\Modules\EntryGuides\Domain\Interfaces\EntryGuideRepositoryInterface;



class FindAllEntryGuideUseCase{
     public function __construct(private readonly EntryGuideRepositoryInterface $entryGuideRepositoryInterface){}
    

     public function execute(?string $description,?int $status, ?int $reference_document_id){
        return $this->entryGuideRepositoryInterface->findAll($description, $status, $reference_document_id);
     }
    }